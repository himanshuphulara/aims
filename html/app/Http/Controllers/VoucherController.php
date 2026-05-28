<?php

namespace App\Http\Controllers;

use App\Models\BankStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Cheque;
use App\Models\CIV;
use App\Models\CRV;
use App\Models\Items;
use App\Models\Messbill;
use App\Models\NIV;
use App\Models\Properties;
use App\Models\SyCr;
use App\Models\SyDr;
use App\Models\TotalAllotment;
use App\Models\PcdaTransaction;
use App\Models\Voucher;
use App\Models\User;
use App\Models\Unit;
use App\Models\VoucherBBF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    // public function voucherFunds()
    // {
    //     $categories = Category::get();
    // }

    public function getVoucher($id,Request $req)
    {
        $isExist = Category::where('id',$id)->first();
        if(empty($isExist)){
            // session()->flash('error', 'Category does not exist!!!');
            return redirect('dashboard');
        }
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date;
            $endOfMonth = $req->end_date;
        }else{
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
        }
        $user = User::find(Auth::id());
        $admin = $user->isAdmin();
        $getname = Category::where('id',$id)->get();
        $subcategories = Category::where('parent_id',$id)->get();
        $fundfor = $getname[0]->name;
        $cat_id = $getname[0]->id;
        $categories = Category::where('parent_id',$cat_id)->get(['name','type']);
        $vouchers = $admin?Voucher::where('category_id',$cat_id)->whereBetween('voc_date',[$startOfMonth,$endOfMonth])->where('voc_type','Receipt'):Voucher::where('category_id',$cat_id)->where('user_id',Auth::id())->whereBetween('voc_date',[$startOfMonth,$endOfMonth])->where('voc_type','Receipt');
        if($req->has('search')){
            $vouchers->where(function($query) use($req) {
                $query->where('voc_no', 'LIKE', "%{$req->search}%" )
                    ->orWhere ( 'voc_whom', 'LIKE', "%{$req->search}%" )
                    ->orWhere ( 'voc_acc', 'LIKE', "%{$req->search}%" );
            });
        }
        $vouchers = $vouchers->orderBy('id','asc')->get();
        $sycrs = SyCr::where('category_id',$id)->whereBetween('sycr_date',[$startOfMonth,$endOfMonth])->get();
        $crvs_properties = CRV::where('category_id',$cat_id)->whereBetween('dated',[$startOfMonth,$endOfMonth])->get(['crv']);
        // print_r($crvs_properties->toArray());
        $sydrs = SyDr::where('category_id',$id)->whereBetween('sydr_date',[$startOfMonth,$endOfMonth])->get();
        $properties = Properties::where('category_id',$id)->whereBetween('pro_date',[$startOfMonth,$endOfMonth])->get();
        $cheques = Cheque::where(['category_id'=>$id,'cheque_status'=>0])->whereBetween('cheque_date',[$startOfMonth,$endOfMonth])->get();
        $stmt = BankStatement::where('category_id',$id)->whereBetween('stmt_date',[$startOfMonth,$endOfMonth])->get();
        
        // Get ALL Sy Cr and Sy Dr records (without date filter)
        $allSyCrs = SyCr::where('category_id',$id)->orderBy('sycr_date','asc')->get();
        $allSyDrs = SyDr::where('category_id',$id)->orderBy('sydr_date','asc')->get();
        
        // Get ALL uncleared cheques (without date range filter)
        $allUnclearedCheques = Cheque::where(['category_id'=>$id,'cheque_status'=>0])->orderBy('cheque_date','asc')->get();
        $title = 'Receipt (Credit) - '.$fundfor;
        $req->session()->put('secondlasturl', $_SERVER['REQUEST_URI']);
        $bbf = $this->lastMonthBalace($cat_id,$fundfor,$startOfMonth,$endOfMonth,'Receipt');
        $bbf_for_crv = $this->lastMonthBalace($cat_id,$fundfor,$startOfMonth,$endOfMonth,'Payment');
        $messbill =  VoucherBBF::select('voc_cash','voc_bank','voc_date','voc_json','voc_memo_stk','voc_property')
        ->where(['category_id'=>$cat_id,'voc_type'=>'Messbill'])
        ->whereBetween('voc_date', [$startOfMonth,$endOfMonth])
        ->first();
        $messbillsydrs =  Messbill::select('officer_rank','officer_name','bill_total','round_off')
        ->where(['category_id'=>$cat_id])
        ->whereBetween('bill_date', [$startOfMonth,$endOfMonth])
        ->get();
        $paymentBalance = $this->selectedMonthPaymentBalance($cat_id,$fundfor,$startOfMonth,$endOfMonth);
        $paymentTotal = $this->selectedMonthPaymentTotal($cat_id,$fundfor,$startOfMonth,$endOfMonth);
        $categoryInfo = Category::find($cat_id);
        
        // Get existing Total Allotment records for this category and fund type
        $totalAllotments = TotalAllotment::with(['subcategory', 'user'])
            ->where('category_id', $cat_id)
            ->where('fund_type', $fundfor)
            ->orderBy('allotment_date', 'desc')
            ->get();
            
        // Get existing PCDA transactions for this category and fund type
        $pcdaTransactions = PcdaTransaction::with(['subcategory', 'user'])
            ->where('category_id', $cat_id)
            ->where('fund_type', $fundfor)
            ->orderBy('transaction_date', 'desc')
            ->get();
            
        // Format dates for view
        $start = $startOfMonth;
        $end = $endOfMonth;
            
        return view('vouchers.debitreceipt',compact('title','subcategories','fundfor','cat_id','categories','vouchers','bbf','paymentBalance','sycrs','sydrs','stmt','cheques','properties','messbill','paymentTotal','messbillsydrs','bbf_for_crv','crvs_properties','categoryInfo','totalAllotments','pcdaTransactions','allUnclearedCheques','allSyCrs','allSyDrs','start','end'));
    }

    public function getVoucherp($id,Request $req)
    {
        $isExist = Category::where('id',$id)->first();
        if(empty($isExist)){
            // session()->flash('error', 'Category does not exist!!!');
            return redirect('dashboard');
        }
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date;
            $endOfMonth = $req->end_date;
        }else{
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
        }
        $user = User::find(Auth::id());
        $admin = $user->isAdmin();
        $getname = Category::where('id',$id)->get();
        $subcategories = Category::where('parent_id',$id)->get();
        $fundfor = $getname[0]->name;
        $cat_id = $getname[0]->id;
        $categories = Category::where('parent_id',$cat_id)->get(['name','type']);
        $vouchers = $admin?Voucher::where('category_id',$cat_id)->whereBetween('voc_date',[$startOfMonth,$endOfMonth])->where('voc_type','Payment'):Voucher::where('category_id',$cat_id)->where('user_id',Auth::id())->whereBetween('voc_date',[$startOfMonth,$endOfMonth])->where('voc_type','Payment');

        if($req->has('search')){
            $vouchers->where(function($query) use($req) {
                $query->where('voc_no', 'LIKE', "%{$req->search}%" )
                    ->orWhere ( 'voc_whom', 'LIKE', "%{$req->search}%" )
                    ->orWhere ( 'voc_acc', 'LIKE', "%{$req->search}%" );
            });
        }
        $vouchers = $vouchers->orderBy('id','asc')->get();
        $title = 'Payment (Debit) - '.$fundfor;
        $req->session()->put('secondlasturl', $_SERVER['REQUEST_URI']);
        $bbf = $this->lastMonthBalace($cat_id,$fundfor,$startOfMonth,$endOfMonth,'Payment');
        $fromreceipt = $this->selectedMonthReceiptGrandTotal($cat_id,$fundfor,$startOfMonth,$endOfMonth);
        $paymentBalance = $this->selectedMonthPaymentBalance($cat_id,$fundfor,$startOfMonth,$endOfMonth);
        $messbilltotaldr = $this->selectedMonthMessBillTotalForSyDR($cat_id,$fundfor,$startOfMonth,$endOfMonth);
        
        // Format dates for view
        $start = $startOfMonth;
        $end = $endOfMonth;
        
        return view('vouchers.creditpayment',compact('title','subcategories','fundfor','cat_id','categories','vouchers','bbf','fromreceipt','messbilltotaldr','start','end'));
        // return view('vouchers.creditpaymentexceltest',compact('title','subcategories','fundfor','cat_id','categories','vouchers','bbf'));
    }

    public function voucherAdd(Request $req)
    {
        // Validate the form data
        $validator = Validator::make($req->all(), [
            'voc_date' => 'required|date',
            'voc_no' => 'required|string|max:255',
            'voc_file' => 'required|file|mimes:jpg,png,pdf|max:2048',
            'voc_whom' => 'required|string|max:255',
            'voc_acc' => 'required|string|max:255',
            'voc_cash' => 'required|numeric|min:0',
            'voc_bank' => 'required|numeric|min:0',
        ],[
            'voc_no.required'=>'voucher number required.',
            'voc_file.required'=>'voucher required.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($req->hasFile('voc_file')) {
            $file = $req->file('voc_file');
            $filePath = $file->store('vouchers', 'public');
        }else{
            $filePath='';
        }
        // $message = $filePath;
        // $data = $req->except('_token');
        // $data['voc_file'] = $filePath;
        $voc = new Voucher();
        $voc_json = [];
        $cats = Category::where('parent_id',$req->cat_id)->get('name');
        foreach($cats as $cat){
            $name = strtolower(preg_replace('/\s+/', '_', trim($cat['name'])));
            $voc_json[$name] = $req->$name;
        }
        $voc->category_id = $req->cat_id;
        $voc->user_id = Auth::id();
        $voc->voc_fund_type = $req->voc_fund_type;
        $voc->voc_type = $req->voc_type;
        $voc->voc_date = $req->voc_date;
        $voc->voc_no = $req->voc_no;
        $voc->voc_file = $filePath;
        $voc->voc_whom = $req->voc_whom;
        $voc->voc_acc = $req->voc_acc;
        $voc->voc_cash = $req->voc_cash;
        $voc->voc_bank = $req->voc_bank;
        $voc->voc_json = $voc_json;
        $voc->voc_memo_stk = $req->voc_memo_stk;
        $voc->voc_property = $req->voc_property;
        if ($voc->save()){
            $user = User::all();
            Notification::send($user, new UserNotification($req->voc_fund_type,Auth::user()->name,"Voucher Added By ".Auth::user()->name,''));
            return response()->json(["success"=>200,"message"=>"Voucher Added Successfully!!!"]);
        }else{
            return response()->json(["error"=>200,"message"=>"Please Try Again Later!!!"]);
        }
    }

    public function voucherEdit($id)
    {
        $voucher = Voucher::find($id);
        $title = "Edit Voucher";
        //$subcategories = Category::where('parent_id',$voucher->category_id)->get();
        return view('vouchers.voucheredit',compact('title','voucher'));
    }

    public function voucherUpdate(Request $req)
    { //return $req->session()->get('secondlasturl');
        $voc = Voucher::find($req->id);
        $voc_json = [];
        $cats = Category::where('parent_id',$req->cat_id)->get('name');
        foreach($cats as $cat){
            $name = strtolower(preg_replace('/\s+/', '_', trim($cat['name'])));
            $voc_json[$name] = $req->$name;
        }
        if ($req->hasFile('voc_file')) {
            $file = $req->file('voc_file');
            $filePath = $file->store('vouchers', 'public');
        }else{
            $filePath=$req->filePath;
        }
        $route = $req->session()->get('secondlasturl');
        if($voc){
            $voc->category_id = $req->cat_id;
            $voc->user_id = Auth::id();
            $voc->voc_fund_type = $req->voc_fund_type;
            $voc->voc_type = $req->voc_type;
            $voc->voc_date = $req->voc_date;
            $voc->voc_no = $req->voc_no;
            $voc->voc_file = $filePath;
            $voc->voc_whom = $req->voc_whom;
            $voc->voc_acc = $req->voc_acc;
            $voc->voc_cash = $req->voc_cash;
            $voc->voc_bank = $req->voc_bank;
            $voc->voc_json = $voc_json;
            $voc->voc_memo_stk = $req->voc_memo_stk;
            $voc->voc_property = $req->voc_property;
            $voc->save();            
            session()->flash('success', 'Voucher Details Updated Successfully!!!');
            return redirect($route);
        }else{
            session()->flash('error', 'Voucher Not Found!!!');
            return redirect($route);
        }
    }

    public function voucherDelete($id,$cat_id)
    {
        $voc = Voucher::find($id);
        // $route = 'voucher/'.$cat_id.'';
        $route = session('secondlasturl');
        if($voc->delete()){
            session()->flash('success', 'Voucher Deleted Successfully!!!');
            return redirect($route);
        }else{
            session()->flash('error', 'Voucher Not Found!!!');
            return redirect($route);
        }
    }

    public function crv($cat_id)
    {
        $getname = Category::where('id',$cat_id)->get();
        $crvs = CRV::where('category_id',$cat_id)->orderBy('id','desc')->get();
        $fundfor = $getname[0]->name;
        // $cat_id = $getname[0]->id;
        $title = 'CRV for - '.$fundfor;
        return view('vouchers.crv.crvs',compact('title','crvs','fundfor','cat_id'));
    }

    public function crvAdd(Request $req)
    {
        if ($req->hasFile('crv_upload')) {
            $file = $req->file('crv_upload');
            $filePath = $file->store('crvs', 'public');
        }else{
            $filePath=$req->filePath;
        }
        $json_data = json_decode($req->jsonData);
        $crv = new CRV();
        $crv->category_id = $req->cat_id;
        $crv->voc_id = $req->voc_id;
        $crv->user_id = Auth::id();
        $crv->property_type = 'crvs';
        $crv->issue_voc_no = $req->issue_voc_no;
        $crv->issue_expense = $req->issue_expense;
        $crv->issue_unit = $req->issue_unit;
        $crv->issue_station = $req->issue_station;
        $crv->receipt_voc_no = $req->receipt_voc_no;
        $crv->receipt_date = $req->receipt_date;
        $crv->receipt_unit = $req->receipt_unit;
        $crv->receipt_station = $req->receipt_station;
        $crv->purchase_from = $req->purchase_from;
        $crv->for_fy = $req->for_fy;
        $crv->bill_no = $req->bill_no;
        $crv->gem = $req->gem;
        $crv->dt = $req->dt;
        $crv->contact_no = $req->contact_no;
        $crv->gemcrac = $req->gemcrac;
        $crv->dated = $req->dated;
        // $crv->crv = $req->jsonData;
        $crv->holder_sign = $req->holder_sign;
        $crv->crv_upload = $filePath;
        if($crv->save()){
            foreach($json_data as $json)
            {
                Items::create([
                    'parent_item_id' => $crv->id,
                    'category_id' => $req->cat_id,
                    'user_id' => Auth::id(),
                    'property_type' => 'crvs',
                    'lpno' => $json->lpno,
                    'items' => $json->items,
                    'au' => $json->au,
                    'date' => $json->date,
                    'qty' => $json->qty,
                    'rate' => $json->rate,
                    'amt' => $json->amt,
                ]);
            }
            session()->flash('success', 'CRV Details Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }             
    }

    public function crvEdit($crv_id)
    {
        $crvs = CRV::find($crv_id);
        $title = "Edit CRV";
        //$subcategories = Category::where('parent_id',$voucher->category_id)->get();
        return view('vouchers.crv.crvedit',compact('title','crvs','crv_id'));
    }

    public function crvPdf($crv_id)
    {
        $crvs = CRV::find($crv_id);
        $title = "Edit CRV";
        $pdf = PDF::loadView('vouchers.crv.crvpdf',['crvs' => $crvs,'title'=>$title]);
        $date = date('d-m-Y_H:i:s');
        return $pdf->download('crvpdf-'.$date.'.pdf');
        // return view('vouchers.crv.crvpdf',compact('title','crvs','crv_id'));
    }

    public function crvUpdate(Request $req)
    {
        if ($req->hasFile('crv_upload')) {
            $file = $req->file('crv_upload');
            $filePath = $file->store('crvs', 'public');
        }else{
            $filePath=$req->filePath;
        }
        $json_data = json_decode($req->jsonData);
        $crv = CRV::find($req->crv_id);
        $crv->category_id = $req->cat_id;
        $crv->voc_id = $req->voc_id;
        $crv->user_id = Auth::id();
        $crv->issue_voc_no = $req->issue_voc_no;
        $crv->issue_expense = $req->issue_expense;
        $crv->issue_unit = $req->issue_unit;
        $crv->issue_station = $req->issue_station;
        $crv->receipt_voc_no = $req->receipt_voc_no;
        $crv->receipt_date = $req->receipt_date;
        $crv->receipt_unit = $req->receipt_unit;
        $crv->receipt_station = $req->receipt_station;
        $crv->purchase_from = $req->purchase_from;
        $crv->for_fy = $req->for_fy;
        $crv->bill_no = $req->bill_no;
        $crv->gem = $req->gem;
        $crv->dt = $req->dt;
        $crv->contact_no = $req->contact_no;
        $crv->gemcrac = $req->gemcrac;
        $crv->dated = $req->dated;
        // $crv->crv = $req->jsonData;
        $crv->holder_sign = $req->holder_sign;
        $crv->crv_upload = $filePath;
        if($crv->save()){
            // Items::where('parent_item_id',$crv->id)->where('property_type','crvs')->delete();
            foreach($json_data as $json)
            {
                DB::table('items')
                ->updateOrInsert(
                    ['id' => $json->itemid],
                    [
                        'parent_item_id' => $crv->id,
                        'category_id' => $req->cat_id,
                        'user_id' => Auth::id(),
                        'property_type' => 'crvs',
                        'lpno' => $json->lpno,
                        'items' => $json->items,
                        'au' => $json->au,
                        'date' => $json->date,
                        'qty' => $json->qty,
                        'rate' => $json->rate,
                        'amt' => $json->amt,
                    ]
                );
            }
            session()->flash('success', 'CRV Details Updated Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }  
    }

    public function crvDelete($crv_id)
    {
        $crv = CRV::find($crv_id);
        if($crv->delete()){
            Items::where('parent_item_id',$crv->id)->where('property_type','crvs')->delete();
            session()->flash('success', 'CRV Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'CRV Not Found!!!');
            return redirect()->back();
        }
    }

    public function niv($cat_id)
    {
        $getname = Category::where('id',$cat_id)->get();
        $nivs = NIV::where('category_id',$cat_id)->orderBy('id','desc')->get();
        $fundfor = $getname[0]->name;
        $cat_id = $getname[0]->id;
        $title = 'NIV for - '.$fundfor;
        return view('vouchers.niv.nivs',compact('title','nivs','fundfor','cat_id'));
    }

    public function nivAdd(Request $req)
    {
        if ($req->hasFile('niv_upload')) {
            $file = $req->file('niv_upload');
            $filePath = $file->store('nivs', 'public');
        }else{
            $filePath=$req->filePath;
        }
        $json_data = json_decode($req->jsonData);
        $niv = new NIV();
        $niv->category_id = $req->cat_id;
        // $niv->voc_id = $req->voc_id;
        $niv->user_id = Auth::id();
        $niv->property_type = 'nivs';
        $niv->issue_voc_no = $req->issue_voc_no;
        $niv->issue_date = $req->issue_date;
        $niv->issue_unit = $req->issue_unit;
        $niv->issue_station = $req->issue_station;
        $niv->receipt_voc_no = $req->receipt_voc_no;
        $niv->receipt_date = $req->receipt_date;
        $niv->receipt_unit = $req->receipt_unit;
        $niv->receipt_station = $req->receipt_station;
        $niv->issued_by = $req->issued_by;
        $niv->received_by = $req->received_by;
        $niv->sig = $req->sig;
        $niv->no = $req->no;
        $niv->rank = $req->rank;
        $niv->name = $req->name;
        $niv->date = $req->date;
        // $niv->niv = $req->jsonData; 
        $niv->niv_upload = $filePath; 
        if($niv->save()){
            foreach($json_data as $json)
            {
                Items::create([
                    'parent_item_id' => $niv->id,
                    'category_id' => $req->cat_id,
                    'user_id' => Auth::id(),
                    'property_type' => 'nivs',
                    'lpno' => $json->lpno,
                    'items' => $json->items,
                    'au' => $json->au,
                    'date' => $json->date,
                    'qty' => $json->qty,
                    'rate' => $json->rate,
                    'amt' => $json->amt,
                ]);
            }
            session()->flash('success', 'NIV Details Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }         
    }

    public function nivPdf($niv_id)
    {
        $nivs = NIV::find($niv_id);
        $title = "Edit NIV";
        $pdf = PDF::loadView('vouchers.niv.nivpdf',['nivs' => $nivs,'title'=>$title]);
        $date = date('d-m-Y_H:i:s');
        return $pdf->download('nivpdf-'.$date.'.pdf');
        // return view('vouchers.niv.nivpdf',compact('title','nivs','niv_id'));
    }

    public function nivEdit($niv_id)
    {
        $nivs = NIV::find($niv_id);
        $title = "Edit NIV";
        //$subcategories = Category::where('parent_id',$voucher->category_id)->get();
        return view('vouchers.niv.nivedit',compact('title','nivs','niv_id'));
    }

    public function nivUpdate(Request $req)
    {
        if ($req->hasFile('niv_upload')) {
            $file = $req->file('niv_upload');
            $filePath = $file->store('nivs', 'public');
        }else{
            $filePath=$req->filePath;
        }
        $json_data = json_decode($req->jsonData);
        $niv = NIV::find($req->niv_id);
        $niv->category_id = $req->cat_id;
        $niv->voc_id = $req->voc_id;
        $niv->user_id = Auth::id();
        $niv->issue_voc_no = $req->issue_voc_no;
        $niv->issue_date = $req->issue_date;
        $niv->issue_unit = $req->issue_unit;
        $niv->issue_station = $req->issue_station;
        $niv->receipt_voc_no = $req->receipt_voc_no;
        $niv->receipt_date = $req->receipt_date;
        $niv->receipt_unit = $req->receipt_unit;
        $niv->receipt_station = $req->receipt_station;
        $niv->issued_by = $req->issued_by;
        $niv->received_by = $req->received_by;
        $niv->sig = $req->sig;
        $niv->no = $req->no;
        $niv->rank = $req->rank;
        $niv->name = $req->name;
        $niv->date = $req->date;
        // $niv->niv = $req->jsonData;
        $niv->niv_upload = $filePath;
        if($niv->save()){
            foreach($json_data as $json)
            {
                DB::table('items')
                ->updateOrInsert(
                    ['id' => $json->itemid],
                    [
                        'parent_item_id' => $niv->id,
                        'category_id' => $req->cat_id,
                        'user_id' => Auth::id(),
                        'property_type' => 'nivs',
                        'lpno' => $json->lpno,
                        'items' => $json->items,
                        'au' => $json->au,
                        'date' => $json->date,
                        'qty' => $json->qty,
                        'rate' => $json->rate,
                        'amt' => $json->amt,
                    ]
                );
            }
            session()->flash('success', 'NIV Details Updated Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        } 
    }

    public function nivDelete($niv_id)
    {
        $niv = NIV::find($niv_id);
        if($niv->delete()){
            Items::where('parent_item_id',$niv->id)->where('property_type','nivs')->delete();
            session()->flash('success', 'NIV Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'NIV Not Found!!!');
            return redirect()->back();
        }
    }

    public function civ($cat_id)
    {
        $getname = Category::where('id',$cat_id)->get();
        $civs = CIV::where('category_id',$cat_id)->orderBy('id','desc')->get();
        $fundfor = $getname[0]->name;
        $cat_id = $getname[0]->id;
        $title = 'CIV for - '.$fundfor;
        return view('vouchers.civ.civs',compact('title','civs','fundfor','cat_id'));
    }

    public function civAdd(Request $req)
    {
        if ($req->hasFile('civ_upload')) {
            $file = $req->file('civ_upload');
            $filePath = $file->store('civs', 'public');
        }else{
            $filePath=$req->filePath;
        }
        $json_data = json_decode($req->jsonData);
        $civ = new CIV();
        $civ->category_id = $req->cat_id;
        // $civ->voc_id = $req->voc_id;
        $civ->user_id = Auth::id();
        $civ->property_type = 'civs';
        $civ->issue_voc_no = $req->issue_voc_no;
        $civ->issue_date = $req->issue_date;
        $civ->issue_unit = $req->issue_unit;
        $civ->issue_station = $req->issue_station;
        $civ->receipt_voc_no = $req->receipt_voc_no;
        $civ->receipt_date = $req->receipt_date;
        $civ->receipt_unit = $req->receipt_unit;
        $civ->receipt_station = $req->receipt_station;
        // $civ->civ = $req->jsonData; 
        $civ->civ_upload = $filePath; 
        if($civ->save()){
            foreach($json_data as $json)
            {
                Items::create([
                    'parent_item_id' => $civ->id,
                    'category_id' => $req->cat_id,
                    'user_id' => Auth::id(),
                    'property_type' => 'civs',
                    'lpno' => $json->lpno,
                    'items' => $json->items,
                    'au' => $json->au,
                    'date' => $json->date,
                    'qty' => $json->qty,
                    'rate' => $json->rate,
                    'amt' => $json->amt,
                ]);
            }
            session()->flash('success', 'CIV Details Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }         
    }

    public function civPdf($civ_id)
    {
        $civs = CIV::find($civ_id);
        $title = "Edit CIV";
        $pdf = PDF::loadView('vouchers.civ.civpdf',['civs' => $civs,'title'=>$title]);
        $date = date('d-m-Y_H:i:s');
        return $pdf->download('civpdf-'.$date.'.pdf');
        // return view('vouchers.niv.nivpdf',compact('title','nivs','niv_id'));
    }

    public function civEdit($civ_id)
    {
        $civs = CIV::find($civ_id);
        $title = "Edit CIV";
        //$subcategories = Category::where('parent_id',$voucher->category_id)->get();
        return view('vouchers.civ.civedit',compact('title','civs','civ_id'));
    }

    public function civUpdate(Request $req)
    {
        if ($req->hasFile('civ_upload')) {
            $file = $req->file('civ_upload');
            $filePath = $file->store('civs', 'public');
        }else{
            $filePath=$req->filePath;
        }
        $json_data = json_decode($req->jsonData);
        $civ = CIV::find($req->civ_id);
        $civ->category_id = $req->cat_id;
        // $civ->voc_id = $req->voc_id;
        $civ->user_id = Auth::id();
        $civ->issue_voc_no = $req->issue_voc_no;
        $civ->issue_date = $req->issue_date;
        $civ->issue_unit = $req->issue_unit;
        $civ->issue_station = $req->issue_station;
        $civ->receipt_voc_no = $req->receipt_voc_no;
        $civ->receipt_date = $req->receipt_date;
        $civ->receipt_unit = $req->receipt_unit;
        $civ->receipt_station = $req->receipt_station;
        // $civ->civ = $req->jsonData; 
        $civ->civ_upload = $filePath; 
        if($civ->save()){
            foreach($json_data as $json)
            {
                DB::table('items')
                ->updateOrInsert(
                    ['id' => $json->itemid],
                    [
                        'parent_item_id' => $civ->id,
                        'category_id' => $req->cat_id,
                        'user_id' => Auth::id(),
                        'property_type' => 'civs',
                        'lpno' => $json->lpno,
                        'items' => $json->items,
                        'au' => $json->au,
                        'date' => $json->date,
                        'qty' => $json->qty,
                        'rate' => $json->rate,
                        'amt' => $json->amt,
                    ]
                );
            }
            session()->flash('success', 'CIV Details Updated Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        } 
    }

    public function civDelete($civ_id)
    {
        $civ = CIV::find($civ_id);
        if($civ->delete()){
            Items::where('parent_item_id',$civ->id)->where('property_type','civs')->delete();
            session()->flash('success', 'CIV Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'CIV Not Found!!!');
            return redirect()->back();
        }
    }

    public function crvLedger($vocid){
        $title = 'CRV Ledger';
        return view('vouchers.crv.crvledger',compact('title'));
    }
    
    public function crvLedgerData(Request $req,$vocid)
    {
        date_default_timezone_set('Asia/Kolkata');
        $searchTerm = $req->get('search', '')['value'];
        $length  = $req->get('length');
        $start = $req->get('start'); 
        $orderColumnIndex = $req->get('order')[0]['column'];
        $orderDirection = $req->get('order')[0]['dir'];

        //Date Filter
        $ndays = $req->ndays;        
        $filters = $this->filterCase($ndays,$req); 
        $start_at = $filters['start_at'];
        $ends_at = $filters['ends_at'];

        $query = CRV::query();
        $crvledger = $query->where('voc_id',$vocid)->get();
        if ($crvledger->isEmpty()) {
            return response()->json([
                'draw' => intval($req->get('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0, // No records after filtering
                'data' => [] // Return empty data
            ]);
        }
        foreach($crvledger as $crv){
            $data[] = json_decode($crv->crv,true);            
        }
        $flattenedData = array_merge(...$data);        

        if ($searchTerm) {
            $flattenedData = array_filter($flattenedData, function ($item) use ($searchTerm) {
                return stripos($item['lpno'], $searchTerm) !== false ||
                    stripos($item['items'], $searchTerm) !== false ||
                    stripos($item['au'], $searchTerm) !== false ||
                    stripos($item['date'], $searchTerm) !== false ||
                    stripos($item['qty'], $searchTerm) !== false ||
                    stripos($item['rate'], $searchTerm) !== false ||
                    stripos($item['amt'], $searchTerm) !== false;
            });
        }  
        $flattenedData = array_filter($flattenedData, function ($item) use ($start_at,$ends_at) {
        if (isset($item['date']) && $item['date'] >= $start_at && $item['date'] <= $ends_at) {
           return $flattenedData[] = $item;
        }
        });
        $columns = ['lpno','items','au','date','qty','rate','amt'];
        usort($flattenedData, function ($a, $b) use ($orderColumnIndex, $columns, $orderDirection) {
            $key = $columns[$orderColumnIndex];
            if ($orderDirection === 'asc') {
                return $a[$key] <=> $b[$key];
            } else {
                return $b[$key] <=> $a[$key];
            }
        });

        $total = count($flattenedData);
        if ($length == -1) {
            $length = $total; // Show all data
        }
        $dataToShow = array_slice($flattenedData, $start, $length);
        return response()->json([
            'draw' => intval($req->get('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $dataToShow
        ]);
    }

    /**
     * Crv Ledger data From route crvledger
     */
    public function crvLedgerPdfData(Request $req,$vocid,$fundfor)
    {
        date_default_timezone_set('Asia/Kolkata');
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date.' 00:00:00';
            $endOfMonth = $req->end_date.' 23:59:59';
        }else{
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
        }
        $searchTerm = $req->search;
        $title = $fundfor.' Ledger';
        $crvledger = Items::where('category_id',$vocid)->where('property_type','crvs')->where('date','>=',$startOfMonth)->where('date','<=',$endOfMonth);
        if($req->has('search')){
            $crvledger->where(function($query) use($req) {
                $query->where('lpno', '=', "%{$req->search}%" )
                    ->orWhere ( 'items', 'LIKE', "%{$req->search}%" );
            });
        }
        $crvledger = $crvledger->orderBy('id','desc')->get();
        

        return view('vouchers.crv.crvledger',compact('title','crvledger','fundfor'));
    }

    /**
     * CRV Ledger Pdf Download from route crvledgerdatadownload
     */
    public function crvLedgerPdfDataDownload(Request $req,$vocid)
    {
        date_default_timezone_set('Asia/Kolkata');
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date.' 00:00:00';
            $endOfMonth = $req->end_date.' 23:59:59';
            $start = date('d F Y',strtotime($startOfMonth));
            $end = date('t F Y',strtotime($endOfMonth));
        }else{
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
            $start = date('d F Y',strtotime($startOfMonth));
            $end = date('t F Y',strtotime($endOfMonth));
        }
        // return [$start,$end];
        $searchTerm = $req->search;
        $title = 'CRV Ledger';
        $crvledger = Items::where('category_id',$vocid)->where('property_type','crvs')->where('date','>=',$startOfMonth)->where('date','<=',$endOfMonth);
        if($req->has('search')){
            $crvledger->where(function($query) use($req) {
                $query->where('lpno', '=', "%{$req->search}%" )
                    ->orWhere ( 'items', 'LIKE', "%{$req->search}%" );
            });
        }
        $crvledger = $crvledger->orderBy('id','desc')->get();
        $pdf = PDF::loadView('vouchers.crv.crvledgerdatadownload',['crvledger' => $crvledger,'title'=>$title,'start'=>$start,'end'=>$end]);
        $date = date('d-m-Y_H:i:s');
        return $pdf->download('crvledger-'.$date.'.pdf');
        // return view('vouchers.crv.crvledgerdatadownload',compact('title','crvledger','start','end'));
    }

    public function nivLedger($vocid){
        $title = 'NIV Ledger';
        return view('vouchers.niv.nivledger',compact('title'));
    }

    public function nivLedgerData(Request $req,$vocid)
    {
        date_default_timezone_set('Asia/Kolkata');
        $searchTerm = $req->get('search', '')['value'];
        $length  = $req->get('length');
        $start = $req->get('start'); 
        $orderColumnIndex = $req->get('order')[0]['column'];
        $orderDirection = $req->get('order')[0]['dir'];

        //Date Filter
        $ndays = $req->ndays;        
        $filters = $this->filterCase($ndays,$req); 
        $start_at = $filters['start_at'];
        $ends_at = $filters['ends_at'];

        $query = NIV::query();
        $nivledger = $query->where('voc_id',$vocid)->get();
        if ($nivledger->isEmpty()) {
            return response()->json([
                'draw' => intval($req->get('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0, // No records after filtering
                'data' => [] // Return empty data
            ]);
        }
        foreach($nivledger as $niv){
            $data[] = json_decode($niv->niv,true);            
        }
        $flattenedData = array_merge(...$data);        

        if ($searchTerm) {
            $flattenedData = array_filter($flattenedData, function ($item) use ($searchTerm) {
                return stripos($item['lpno'], $searchTerm) !== false ||
                    stripos($item['items'], $searchTerm) !== false ||
                    stripos($item['au'], $searchTerm) !== false ||
                    stripos($item['date'], $searchTerm) !== false ||
                    stripos($item['qty'], $searchTerm) !== false ||
                    stripos($item['rate'], $searchTerm) !== false ||
                    stripos($item['amt'], $searchTerm) !== false;
            });
        }  
        $flattenedData = array_filter($flattenedData, function ($item) use ($start_at,$ends_at) {
        if (isset($item['date']) && $item['date'] >= $start_at && $item['date'] <= $ends_at) {
           return $flattenedData[] = $item;
        }
        });
        $columns = ['lpno','items','au','date','qty','rate','amt'];
        usort($flattenedData, function ($a, $b) use ($orderColumnIndex, $columns, $orderDirection) {
            $key = $columns[$orderColumnIndex];
            if ($orderDirection === 'asc') {
                return $a[$key] <=> $b[$key];
            } else {
                return $b[$key] <=> $a[$key];
            }
        });

        $total = count($flattenedData);
        if ($length == -1) {
            $length = $total; // Show all data
        }
        $dataToShow = array_slice($flattenedData, $start, $length);
        return response()->json([
            'draw' => intval($req->get('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $dataToShow
        ]);
    }

    /**
     * NIV Ledger data From route nivledger
     */
    public function nivLedgerPdfData(Request $req,$vocid)
    {
        date_default_timezone_set('Asia/Kolkata');
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date.' 00:00:00';
            $endOfMonth = $req->end_date.' 23:59:59';
        }else{
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
        }
        $searchTerm = $req->search;
        $title = 'NIV Ledger';
        $nivledger = Items::where('category_id',$vocid)->where('property_type','nivs')->where('date','>=',$startOfMonth)->where('date','<=',$endOfMonth);
        if($req->has('search')){
            $nivledger->where(function($query) use($req) {
                $query->where('lpno', '=', "%{$req->search}%" )
                    ->orWhere ( 'items', 'LIKE', "%{$req->search}%" );
            });
        }
        $nivledger = $nivledger->orderBy('id','desc')->get();

        return view('vouchers.niv.nivledger',compact('title','nivledger'));
    }

    /**
     * NIV Ledger Pdf Download from route nivledgerdatadownload
     */
    public function nivLedgerPdfDataDownload(Request $req,$vocid)
    {
        date_default_timezone_set('Asia/Kolkata');
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date.' 00:00:00';
            $endOfMonth = $req->end_date.' 23:59:59';
            $start = date('d F Y',strtotime($startOfMonth));
            $end = date('t F Y',strtotime($endOfMonth));
        }else{
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
            $start = date('d F Y',strtotime($startOfMonth));
            $end = date('t F Y',strtotime($endOfMonth));
        }
        // return [$start,$end];
        $searchTerm = $req->search;
        $title = 'NIV Ledger';
        $nivledger = Items::where('category_id',$vocid)->where('property_type','nivs')->where('date','>=',$startOfMonth)->where('date','<=',$endOfMonth);
        if($req->has('search')){
            $nivledger->where(function($query) use($req) {
                $query->where('lpno', '=', "%{$req->search}%" )
                    ->orWhere ( 'items', 'LIKE', "%{$req->search}%" );
            });
        }
        $nivledger = $nivledger->orderBy('id','desc')->get();
        $pdf = PDF::loadView('vouchers.niv.nivledgerdatadownload',['nivledger' => $nivledger,'title'=>$title,'start'=>$start,'end'=>$end]);
        $date = date('d-m-Y_H:i:s');
        return $pdf->download('nivledger-'.$date.'.pdf');
        // return view('vouchers.niv.nivledgerdatadownload',compact('title','crvledger','start','end'));
    }

    public function civLedger($vocid){
        $title = 'CIV Ledger';
        return view('vouchers.civ.civledger',compact('title'));
    }

    public function civLedgerData(Request $req,$vocid)
    {
        date_default_timezone_set('Asia/Kolkata');
        $searchTerm = $req->get('search', '')['value'];
        $length  = $req->get('length');
        $start = $req->get('start'); 
        $orderColumnIndex = $req->get('order')[0]['column'];
        $orderDirection = $req->get('order')[0]['dir'];

        //Date Filter
        $ndays = $req->ndays;        
        $filters = $this->filterCase($ndays,$req); 
        $start_at = $filters['start_at'];
        $ends_at = $filters['ends_at'];

        $query = CIV::query();
        $civledger = $query->where('voc_id',$vocid)->get();
        if ($civledger->isEmpty()) {
            return response()->json([
                'draw' => intval($req->get('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0, // No records after filtering
                'data' => [] // Return empty data
            ]);
        }
        foreach($civledger as $civ){
            $data[] = json_decode($civ->civ,true);            
        }
        $flattenedData = array_merge(...$data);        

        if ($searchTerm) {
            $flattenedData = array_filter($flattenedData, function ($item) use ($searchTerm) {
                return stripos($item['lpno'], $searchTerm) !== false ||
                    stripos($item['items'], $searchTerm) !== false ||
                    stripos($item['au'], $searchTerm) !== false ||
                    stripos($item['date'], $searchTerm) !== false ||
                    stripos($item['qty'], $searchTerm) !== false ||
                    stripos($item['rate'], $searchTerm) !== false ||
                    stripos($item['amt'], $searchTerm) !== false;
            });
        }  
        $flattenedData = array_filter($flattenedData, function ($item) use ($start_at,$ends_at) {
        if (isset($item['date']) && $item['date'] >= $start_at && $item['date'] <= $ends_at) {
           return $flattenedData[] = $item;
        }
        });
        $columns = ['lpno','items','au','date','qty','rate','amt'];
        usort($flattenedData, function ($a, $b) use ($orderColumnIndex, $columns, $orderDirection) {
            $key = $columns[$orderColumnIndex];
            if ($orderDirection === 'asc') {
                return $a[$key] <=> $b[$key];
            } else {
                return $b[$key] <=> $a[$key];
            }
        });

        $total = count($flattenedData);
        if ($length == -1) {
            $length = $total; // Show all data
        }
        $dataToShow = array_slice($flattenedData, $start, $length);
        return response()->json([
            'draw' => intval($req->get('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $dataToShow
        ]);
    }

    /**
     * CIV Ledger data From route civledger
     */
    public function civLedgerPdfData(Request $req,$vocid)
    {
        date_default_timezone_set('Asia/Kolkata');
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date.' 00:00:00';
            $endOfMonth = $req->end_date.' 23:59:59';
        }else{
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
        }
        $searchTerm = $req->search;
        $title = 'CIV Ledger';
        $civledger = Items::where('category_id',$vocid)->where('property_type','civs')->where('date','>=',$startOfMonth)->where('date','<=',$endOfMonth);
        if($req->has('search')){
            $civledger->where(function($query) use($req) {
                $query->where('lpno', '=', "%{$req->search}%" )
                    ->orWhere ( 'items', 'LIKE', "%{$req->search}%" );
            });
        }
        $civledger = $civledger->orderBy('id','desc')->get();

        return view('vouchers.civ.civledger',compact('title','civledger'));
    }

    /**
     * NIV Ledger Pdf Download from route nivledgerdatadownload
     */
    public function civLedgerPdfDataDownload(Request $req,$vocid)
    {
        date_default_timezone_set('Asia/Kolkata');
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date.' 00:00:00';
            $endOfMonth = $req->end_date.' 23:59:59';
            $start = date('d F Y',strtotime($startOfMonth));
            $end = date('t F Y',strtotime($endOfMonth));
        }else{
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
            $start = date('d F Y',strtotime($startOfMonth));
            $end = date('t F Y',strtotime($endOfMonth));
        }
        // return [$start,$end];
        $searchTerm = $req->search;
        $title = 'CIV Ledger';
        $civledger = Items::where('category_id',$vocid)->where('property_type','civs')->where('date','>=',$startOfMonth)->where('date','<=',$endOfMonth);
        if($req->has('search')){
            $civledger->where(function($query) use($req) {
                $query->where('lpno', '=', "%{$req->search}%" )
                    ->orWhere ( 'items', 'LIKE', "%{$req->search}%" );
            });
        }
        $civledger = $civledger->orderBy('id','desc')->get();
        $pdf = PDF::loadView('vouchers.civ.civledgerdatadownload',['civledger' => $civledger,'title'=>$title,'start'=>$start,'end'=>$end]);
        $date = date('d-m-Y_H:i:s');
        return $pdf->download('civledger-'.$date.'.pdf');
        // return view('vouchers.niv.nivledgerdatadownload',compact('title','crvledger','start','end'));
    }

    public function filterCase($ndays,$req)
    {
        $startDate  = $endDate = date('Y-m-d');
        switch($ndays){
            case 'custmDateGraph':
                $startDate = $req->startDate;
                $endDate = $req->endDate;
            break;
            case 'td':
                $startDate = $endDate = date('Y-m-d');
            break;
            case 'mtd':
                $startDate = date('Y-m-01');
            break;
            case 'sixty':
                $startDate = "- 60days";
            break;
            case 'ytd':
                $startDate = date('Y-01-01');
            break;
            default:
            $startDate  = $endDate = date('Y-m-d');
        }
        $start_at = date('Y-m-d',strtotime($startDate));
        $ends_at = date('Y-m-d',strtotime($endDate));

        return ['start_at'=>$start_at,'ends_at'=>$ends_at];
    }

    //Last Month Balance
    public function lastMonthBalace($cat_id,$fundfor,$selectedDate,$selectedendDate,$voc_type)
    {
        $yearmonth = date('Y-m',strtotime($selectedDate));
        $selectedMonth = Carbon::createFromFormat('Y-m', $yearmonth);
        $subcategories = Category::where('parent_id',$cat_id)->get('name');
        $cash=$bank=$memostk=$property=$fd=0;$cattotal=[];$date='';
        
        // Calculate the first and last day of the last month and current month
        $startOfLastMonth = $selectedMonth->copy()->subMonth()->startOfMonth()->format('Y-m-25');
        $endOfCurrentMonth = $selectedMonth->copy()->endOfMonth()->format('Y-m-t');
        $lastMonthData = VoucherBBF::select('voc_cash','voc_bank','voc_date','voc_json','voc_memo_stk','voc_property','voc_fd')
                ->where(['category_id'=>$cat_id,'voc_type'=>$voc_type])
                ->whereBetween('voc_date', [$startOfLastMonth,$endOfCurrentMonth])
                ->orderBy('voc_date','desc')
                ->orderBy('id','desc')
                ->first();
        if($lastMonthData){
            $cash = $lastMonthData['voc_cash'];
            $bank = $lastMonthData['voc_bank'];
            $memostk = $lastMonthData['voc_memo_stk'];
            $property = $lastMonthData['voc_property'];
            $fd = $lastMonthData['voc_fd'];
            foreach($lastMonthData['voc_json'] as $key=>$value){
                $cattotal[$key] = $value;
            }
            $date = $lastMonthData['voc_date'];
        }else{
            foreach($subcategories as $sub){
                $cattotal[strtolower(str_replace(" ","_",$sub->name))] = 0;
            }
        }
        return (['voc_cash'=>$cash,'voc_bank'=>$bank,'voc_memo_stk'=>$memostk,'voc_property'=>$property,'voc_fd'=>$fd,'bfftotal'=>$cattotal,'voc_date'=>$date]);
        
    }

    public function selectedMonthReceiptGrandTotal($cat_id,$fundfor,$start,$end)
    {
        $subcategories = Category::where('parent_id',$cat_id)->get('name');
        $cash=$bank=$cashbbf=$bankbbf=$cashmess=$bankmess=$memostk=$property=$memostkbbf=$propertybbf=$memostkmess=$propertymess=$fd=$fdbbf=$fdmess=0;
        $cattotal=$cattotalbbf=$messtotal=[];
        $yearmonth = date('Y-m',strtotime($start));
        $selectedMonth = Carbon::createFromFormat('Y-m', $yearmonth);
        $startOfLastMonth = $selectedMonth->copy()->subMonth()->startOfMonth()->format('Y-m-25');
        $endOfLastMonth = $selectedMonth->copy()->endOfMonth()->format('Y-m-t');

        $messbillData = VoucherBBF::select('voc_cash','voc_bank','voc_date','voc_json','voc_memo_stk','voc_property','voc_fd')
                ->where(['category_id'=>$cat_id,'voc_type'=>'Messbill'])
                ->whereBetween('voc_date', [$start,$end])
                ->orderBy('voc_date','desc')
                ->orderBy('id','desc')
                ->first();
        if($messbillData){
            $cashmess = $messbillData['voc_cash'];
            $bankmess = $messbillData['voc_bank'];
            $memostkmess = $messbillData['voc_memo_stk'];
            $propertymess = $messbillData['voc_property'];
            $fdmess = $messbillData['voc_fd'];
            foreach($messbillData['voc_json']as $key=>$value){
                $messtotal[$key] = $value;
            }
        }else{
            foreach($subcategories as $sub){
                $messtotal[strtolower(str_replace(" ", "_", $sub->name))] = 0;
            }
        }

        $lastMonthData = VoucherBBF::select('voc_cash','voc_bank','voc_date','voc_json','voc_memo_stk','voc_property','voc_fd')
                ->where(['category_id'=>$cat_id,'voc_type'=>'Receipt'])
                ->whereBetween('voc_date', [$startOfLastMonth,$endOfLastMonth])
                ->orderBy('voc_date','desc')
                ->orderBy('id','desc')
                ->first();
        if($lastMonthData){
            $cashbbf = $lastMonthData['voc_cash'];
            $bankbbf = $lastMonthData['voc_bank'];
            $memostkbbf = $lastMonthData['voc_memo_stk'];
            $propertybbf = $lastMonthData['voc_property'];
            $fdbbf = $lastMonthData['voc_fd'];
            foreach($lastMonthData['voc_json'] as $key=>$value){
                $cattotalbbf[$key] = $value;
            }
        }else{
            foreach($subcategories as $sub){
                $cattotalbbf[strtolower(str_replace(" ", "_", $sub->name))] = 0;
            }
        }
        
        $selectedMonthData = Voucher::select('voc_cash','voc_bank','voc_date','voc_json','voc_memo_stk','voc_property','voc_fd')
                ->where(['category_id'=>$cat_id,'voc_type'=>'Receipt'])
                ->whereBetween('voc_date', [$start,$end])
                ->get();
        if(count($selectedMonthData)>0){
            foreach($selectedMonthData as $last)
            {
                $cash+=$last['voc_cash'];
                $bank+=$last['voc_bank'];
                $memostk += $last['voc_memo_stk'];
                $property += $last['voc_property'];
                $fd += $last['voc_fd'];
                $db_voc_json = \App\Helpers\Helper::categories_voc_json($last['voc_json'],$cat_id);
                foreach($db_voc_json as $key=>$value){
                    if($key!=''){
                        if (isset($cattotal[$key])) {
                            $cattotal[$key] += $value;
                        } else {
                            $cattotal[$key] = $value;
                        }
                    }
                }
            }
        }else{
            foreach($subcategories as $sub){
                $cattotal[strtolower(str_replace(" ", "_", $sub->name))] = 0;
            }
        }
        array_walk($cattotalbbf, function(&$value, $key) use ($cattotal) {
            $value += $cattotal[$key] ?? 0;
        });
        array_walk($messtotal, function(&$value, $key) use ($cattotalbbf) {
            $value += $cattotalbbf[$key] ?? 0;
        });
        return (['voc_cash'=>$cash+$cashbbf+$cashmess,'voc_bank'=>$bank+$bankbbf+$bankmess,'voc_memo_stk'=>$memostk+$memostkbbf+$memostkmess,'voc_property'=>$property+$propertybbf+$propertymess,'voc_fd'=>$fd+$fdbbf+$fdmess,'credit'=>$messtotal]);
        
    }

    public function selectedMonthPaymentBalance($cat_id,$fundfor,$start,$end)
    {
        $subcategories = Category::where('parent_id',$cat_id)->get(['name','type']);
        $cash=$bank=$cashbbf=$bankbff=$memostk=$property=$memostkbbf=$propertybbf=$fd=$fdbbf=0;
        $cattotal=$cattotalbbf=[];
        $aslib = [];
        foreach($subcategories as $cate)
        {
            $aslib[strtolower(str_replace(" ","_",$cate->name))] = strtolower($cate->type);
        }
        
        $sydr = $this->selectedMonthMessBillTotalForSyDR($cat_id,$fundfor,$start,$end);
        
        $yearmonth = date('Y-m',strtotime($start));
        $selectedMonth = Carbon::createFromFormat('Y-m', $yearmonth);
        $startOfLastMonth = $selectedMonth->copy()->subMonth()->startOfMonth()->format('Y-m-25');
        $endOfCurrentMonth = $selectedMonth->copy()->endOfMonth()->format('Y-m-t');
        
        $lastMonthData = VoucherBBF::select('voc_cash','voc_bank','voc_date','voc_json','voc_memo_stk','voc_property','voc_fd')
                ->where(['category_id'=>$cat_id,'voc_type'=>'Payment'])
                ->whereBetween('voc_date', [$startOfLastMonth,$endOfCurrentMonth])
                ->orderBy('voc_date','desc')
                ->orderBy('id','desc')
                ->first();
        if($lastMonthData){
            $cashbbf = $lastMonthData['voc_cash'];
            $bankbff = $lastMonthData['voc_bank'];
            $memostkbbf = $lastMonthData['voc_memo_stk'];
            $propertybbf = $lastMonthData['voc_property'];
            $fdbbf = $lastMonthData['voc_fd'];
            $db_voc_json = \App\Helpers\Helper::categories_voc_json($lastMonthData['voc_json'],$cat_id);
            foreach($db_voc_json as $key=>$value){
                if($key!=''){
                    $cattotalbbf[$key] = $value;
                }
            }
        }else{
            foreach($subcategories as $sub){
                $cattotalbbf[strtolower(str_replace(" ", "_", $sub->name))] = 0;
            }
        }
        
        $selectedMonthData = Voucher::select('voc_cash','voc_bank','voc_date','voc_json','voc_memo_stk','voc_property','voc_fd')
                ->where(['category_id'=>$cat_id,'voc_type'=>'Payment'])
                ->whereBetween('voc_date', [$start,$end])
                ->get();
        if(count($selectedMonthData)>0){
            foreach($selectedMonthData as $last)
            {
                $cash+=$last['voc_cash'];
                $bank+=$last['voc_bank'];
                $memostk += $last['voc_memo_stk'];
                $property += $last['voc_property'];
                $fd += $last['voc_fd'];
                $db_voc_json = \App\Helpers\Helper::categories_voc_json($last['voc_json'],$cat_id);
                foreach($db_voc_json as $key=>$value){
                    if($key!=''){
                        if (isset($cattotal[$key])) {
                            $cattotal[$key] += $value;
                        } else {
                            $cattotal[$key] = $value;
                    }
                    }
                    //$cattotal[$key] = ($cattotal[$key] ?? 0) + $value;
                }
            }
        }else{
            foreach($subcategories as $sub){
                $cattotal[strtolower($sub->name)] = 0;
            }
        }

        $selectedmonth = $this->selectedMonthReceiptGrandTotal($cat_id,$fundfor,$start,$end);
        $cash = $selectedmonth['voc_cash']-$cash-$cashbbf;
        $bank = $selectedmonth['voc_bank']-$bank-$bankbff;
        $memostk = $selectedmonth['voc_memo_stk']-$memostk-$memostkbbf;
        $property = $property+$propertybbf-$selectedmonth['voc_property'];
        $fd = $fd+$fdbbf-$selectedmonth['voc_fd'];
        // $result = [];
        if(count($selectedMonthData)==0 && count($selectedmonth)==0){
            foreach($subcategories as $sub){
                $cattotal[strtolower(str_replace(" ", "_", $sub->name))] = 0;
            }
        return (['voc_cash'=>$cash,'voc_bank'=>$bank,'voc_memo_stk'=>$memostk,'voc_property'=>$property,'voc_fd'=>$fd,'bfftotal'=>$cattotal]);
        }
        
        if(count($selectedMonthData)>0){
            array_walk($cattotalbbf, function(&$value, $key) use ($cattotal) {
                $value += $cattotal[$key] ?? 0; // Add the values from array2 to array1, keeping the key
            });
            foreach ($selectedmonth['credit'] as $key => $value) {
                if (array_key_exists($key, $cattotalbbf)) {
                    if(isset($aslib[$key]) && $aslib[$key]=='liabilities'){
                        $result[$key] = $value - $cattotalbbf[$key];
                    }else{
                        if($key=="sy_dr"){
                            $result[$key] =  $cattotalbbf[$key] +$sydr - $value;
                        }else{
                            $result[$key] =  $cattotalbbf[$key] -$value;
                        }
                    }
                }else{
                    $result[$key]=0;
                }
            }
            // return $result;
            return (['voc_cash'=>$cash,'voc_bank'=>$bank,'voc_memo_stk'=>$memostk,'voc_property'=>$property,'voc_fd'=>$fd,'bfftotal'=>$result]);
        }else{
            //in case no voucher data entered
            $result = [];
            foreach ($selectedmonth['credit'] as $key => $value) {
                if (array_key_exists($key, $cattotalbbf)) {
                    if(isset($aslib[$key]) && $aslib[$key]=='liabilities'){
                        $result[$key] = $value - $cattotalbbf[$key];
                    }else{
                        if($key=="sy_dr"){
                            $result[$key] =  $cattotalbbf[$key] +$sydr - $value;
                        }else{
                            $result[$key] =  $cattotalbbf[$key] -$value;
                        }
                    }
                }else{
                    $result[$key]=0;
                }
            }
            // return $result;
            return (['voc_cash'=>$cash,'voc_bank'=>$bank,'voc_memo_stk'=>$memostk,'voc_property'=>$property,'voc_fd'=>$fd,'bfftotal'=>$result]);
        }
    }

    public function selectedMonthPaymentTotal($cat_id,$fundfor,$start,$end)
    {
        $subcategories = Category::where('parent_id',$cat_id)->get(['name','type']);
        $cash=$bank=$memostk=$property=$fd=$cashbbf=$bankbbf=$memostkbbf=$propertybbf=$fdbbf=$cashpay=$bankpay=$memostkpay=$propertypay=$fdpay=0;
        $cattotal=$cattotalbbf=$catreceipt=[];
        $aslib = [];
        foreach($subcategories as $cate)
        {
            $aslib[strtolower(str_replace(" ","_",$cate->name))] = strtolower($cate->type);
        }       
        $syrd = $this->selectedMonthMessBillTotalForSyDR($cat_id,$fundfor,$start,$end);
        $yearmonth = date('Y-m',strtotime($start));
        $selectedMonth = Carbon::createFromFormat('Y-m', $yearmonth);
        $startOfLastMonth = $selectedMonth->copy()->subMonth()->startOfMonth()->format('Y-m-25');
        $endOfLastMonth = $selectedMonth->copy()->endOfMonth()->format('Y-m-t');
        
        $paymentBBF = VoucherBBF::select('voc_cash','voc_bank','voc_date','voc_json','voc_memo_stk','voc_property','voc_fd')
                ->where(['category_id'=>$cat_id,'voc_type'=>'Payment'])
                ->whereBetween('voc_date', [$startOfLastMonth,$endOfLastMonth])
                ->orderBy('voc_date','desc')
                ->orderBy('id','desc')
                ->first();
        if($paymentBBF){
            $cashbbf = $paymentBBF['voc_cash'];
            $bankbbf = $paymentBBF['voc_bank'];
            $memostkbbf = $paymentBBF['voc_memo_stk'];
            $propertybbf = $paymentBBF['voc_property'];
            $fdbbf = $paymentBBF['voc_fd'];
            $db_voc_json = \App\Helpers\Helper::categories_voc_json($paymentBBF['voc_json'],$cat_id);
            foreach($db_voc_json as $key=>$value){
                if($key!=''){
                    $cattotalbbf[$key] = $value;
                }
            }
        }else{
            foreach($subcategories as $sub){
                $cattotalbbf[strtolower(str_replace(" ", "_", $sub->name))] = 0;
            }
        }
        // print_r($cattotalbbf);
        $selectedMonthData = Voucher::select('voc_cash','voc_bank','voc_date','voc_json','voc_memo_stk','voc_property','voc_fd')
                ->where(['category_id'=>$cat_id,'voc_type'=>'Payment'])
                ->whereBetween('voc_date', [$start,$end])
                ->get();
        if(count($selectedMonthData)>0){
            foreach($selectedMonthData as $last)
            {
                $cash+=$last['voc_cash'];
                $bank+=$last['voc_bank'];
                $memostk += $last['voc_memo_stk'];
                $property += $last['voc_property'];
                $fd += $last['voc_fd'];
                $db_voc_json = \App\Helpers\Helper::categories_voc_json($last['voc_json'],$cat_id);
                foreach($db_voc_json as $key=>$value){
                    if($key!=''){
                        if (isset($cattotal[$key])) {
                            $cattotal[$key] += $value;
                        } else {
                            $cattotal[$key] = $value;
                    }
                    }
                    //$cattotal[$key] = ($cattotal[$key] ?? 0) + $value;
                }
            }
        }else{
            foreach($subcategories as $sub){
                $cattotal[strtolower(str_replace(" ","_",$sub->name))] = 0;
            }
        }
        // print_r($cattotal);
        if($paymentBBF){
            array_walk($cattotalbbf, function(&$value, $key) use ($cattotal) {
                $value += $cattotal[$key] ?? 0; // Add the values from array2 to array1, keeping the key
            });
            // print_r($cattotalbbf);
        }


        $selectedmonth = $this->selectedMonthReceiptGrandTotal($cat_id,$fundfor,$start,$end);
        if(count($selectedmonth)>0){
                $cashpay=$selectedmonth['voc_cash'];
                $bankpay=$selectedmonth['voc_bank'];
                $memostkpay= $selectedmonth['voc_memo_stk'];
                $propertypay= $selectedmonth['voc_property'];
                $db_voc_json = \App\Helpers\Helper::categories_voc_json($selectedmonth['credit'],$cat_id);
                foreach($db_voc_json as $key=>$value){
                    if($key!=''){
                        if (isset($catreceipt[$key])) {
                            $catreceipt[$key] += $value;
                        } else {
                            $catreceipt[$key] = $value;
                    }
                    }
                    //$cattotal[$key] = ($cattotal[$key] ?? 0) + $value;
                }
        }else{
            foreach($subcategories as $sub){
                $cattotalbbf[strtolower(str_replace(" ", "_", $sub->name))] = 0;
            }
        }
        // print_r($catreceipt);

        // $result = [];
        if(count($selectedMonthData)==0 && count($selectedmonth)==0){
            foreach($subcategories as $sub){
                $cattotal[strtolower(str_replace(" ", "_", $sub->name))] = 0;
            }
        return (['voc_cash'=>$cash,'voc_bank'=>$bank,'voc_memo_stk'=>$memostk,'voc_property'=>$property,'voc_fd'=>$fd,'paymenttotal'=>$cattotal]);
        }
        
        foreach ($cattotalbbf as $key => $value) {
            if (array_key_exists($key, $cattotalbbf)) {
                if(isset($aslib[$key]) && $aslib[$key]=='liabilities'){
                    $result[$key] = $catreceipt[$key] ?? 0;
                }else{
                    if($key=="sy_dr"){
                        $result[$key] =  $cattotalbbf[$key]+$syrd;
                    }else{
                        $result[$key] =  $cattotalbbf[$key];
                    }
                }
            }else{
                $result[$key]=0;
            }
        }
        // print_r($result);
            // return $result;
            return (['voc_cash'=>$cashpay,'voc_bank'=>$bankpay,'voc_memo_stk'=>$memostkpay,'voc_property'=>$property+$propertybbf,'voc_fd'=>$fd+$fdbbf,'paymenttotal'=>$result]);
    }

    public function selectedMonthMessBillTotalForSyDR($cat_id,$fundfor,$startOfMonth,$endOfMonth)
    {
        $forSyDr = Messbill::select('bill_total','round_off')
        ->where(['category_id'=>$cat_id])
        ->whereBetween('bill_date', [$startOfMonth,$endOfMonth])
        ->get();
        $sydrtotal = count($forSyDr)>0?$forSyDr->sum('bill_total')+$forSyDr->sum('round_off'):0;
        return $sydrtotal;
    }
    public function bankReconciliation(Request $req)
    {
        return response()->json(['success' => $req->all()]);
    }

    /**
     * For Inventory Management From Ledger Category
     */
    public function fundVouchers(Request $req,$cat_id)
    {
        date_default_timezone_set('Asia/Kolkata');
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date;
            $endOfMonth = $req->end_date;
        }else{
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
        }
        $getname = Category::where('id',$cat_id)->first();
        $fundfor = $getname->name;
        // $cat_id = $getname->id;
        $searchTerm = $req->search;
        $items = Items::where('category_id',$cat_id)->where('date','>=',$startOfMonth)->where('date','<=',$endOfMonth);
        if($req->has('search')){
            $items->where(function($query) use($req) {
                $query->where('lpno', '=', "{$req->search}" )
                    ->orWhere ( 'au', 'LIKE', "%{$req->search}%" )
                    ->orWhere ( 'items', 'LIKE', "%{$req->search}%" );
            });
        }
        $items = $items->orderBy('date','desc')->get();        
        $title = $fundfor.' - Ledger';
        return view('vouchers.inventoryledger',compact('title','fundfor','cat_id','items'));
    }

    /**
     * For Inventory Management From Vouchers Category
     */
    public function crvVouchers()
    {
        $crvs = CRV::orderBy('created_at','DESC')->get();
        $title = 'CRV';
        return view('vouchers.crv.crvvouchers',compact('title','crvs'));
    }

    /**
     * For Inventory Management From Vouchers Category
     */
    public function nivVouchers()
    {
        $nivs = NIV::orderBy('created_at','DESC')->get();
        $title = 'NIV';
        return view('vouchers.niv.nivvouchers',compact('title','nivs'));
    }

    /**
     * For Inventory Management From Vouchers Category
     */
    public function civVouchers()
    {
        $civs = CIV::orderBy('created_at','DESC')->get();
        $title = 'CIV';
        return view('vouchers.civ.civvouchers',compact('title','civs'));
    }

    /**
     * BBF ADD
     */
    public function autoBbfAdd(Request $req)
    {
        $voc = new VoucherBBF();
        $voc_json = [];
        
        // Auto BBF functionality - switch between Receipt and Payment within same category
        if($req->source_page == 'voucher'){
            // Coming from Receipt page, go to Payment side of same category
            $route = "voucherp/".$req->cat_id."?start_date=".$req->start."&end_date=".$req->end;
        } else {
            // Coming from Payment page, go to Receipt side of same category
            $route = "voucher/".$req->cat_id."?start_date=".$req->start."&end_date=".$req->end;
        }
        
        $cats = Category::where('parent_id', $req->original_cat_id ?? $req->cat_id)->get('name');
        foreach($cats as $cat){
            $name = strtolower(preg_replace('/\s+/', '_', trim($cat['name'])));
            $voc_json[$name] = $req->$name ?? 0;
        }
        $voc->category_id = $req->cat_id;
        $voc->voc_fund_type = $req->voc_fund_type;
        $voc->voc_type = $req->voc_type;
        $voc->voc_date = $req->voc_date;
        $voc->voc_cash = $req->voc_cash;
        $voc->voc_bank = $req->voc_bank;
        $voc->voc_json = $voc_json;
        $voc->voc_memo_stk = $req->voc_memo_stk;
        $voc->voc_property = $req->voc_property;
        $voc->voc_fd = $req->voc_fd;
        if ($voc->save()){
            foreach($cats as $cat){
                $name = strtolower(preg_replace('/\s+/', '_', trim($cat['name'])));
                $voc_json[$name] = 0;
            }
            $data = DB::table('vouchers')
            ->updateOrInsert(
                ['category_id'=>$req->cat_id,'voc_date'=>$req->voc_date,'voc_type'=>$req->voc_type,'voucher_entery'=>0],
                [   
                    'user_id' =>Auth::id(),
                    'category_id' => $req->cat_id,
                    'voc_fund_type' => $req->voc_fund_type,
                    'voc_type' => $req->voc_type,
                    'voc_date' => $req->voc_date,
                    'voc_cash' => 0,
                    'voc_bank' => 0,
                    'voc_json' => json_encode($voc_json),
                    'voc_memo_stk' => 0,
                    'voc_property' => 0,
                    'voucher_entery' => 0
                ]
            );
            session()->flash('success', 'Auto BBF Details Added Successfully!!!');
            return redirect($route);
        }else{
            session()->flash('success', 'Please Try Again Later!!!');
            return redirect($route);
        }
    }

    public function bbfAdd(Request $req)
    {
        $voc = new VoucherBBF();
        $voc_json = [];
        
        // Regular BBF logic - stays within same category
        if($req->voc_type=='Payment'){
            $route = "voucherp/".$req->cat_id."?start_date=".$req->start."&end_date=".$req->end;
        }else{
            $route = "voucher/".$req->cat_id."?start_date=".$req->start."&end_date=".$req->end;
        }
        $cats = Category::where('parent_id',$req->cat_id)->get('name');
        foreach($cats as $cat){
            $name = strtolower(preg_replace('/\s+/', '_', trim($cat['name'])));
            $voc_json[$name] = $req->$name;
        }
        $voc->category_id = $req->cat_id;
        $voc->voc_fund_type = $req->voc_fund_type;
        $voc->voc_type = $req->voc_type;
        $voc->voc_date = $req->voc_date;
        $voc->voc_cash = $req->voc_cash;
        $voc->voc_bank = $req->voc_bank;
        $voc->voc_json = $voc_json;
        $voc->voc_memo_stk = $req->voc_memo_stk;
        $voc->voc_property = $req->voc_property;
        $voc->voc_fd = $req->voc_fd;
        if ($voc->save()){
            foreach($cats as $cat){
                $name = strtolower(preg_replace('/\s+/', '_', trim($cat['name'])));
                $voc_json[$name] = 0;
            }
            $data = DB::table('vouchers')
            ->updateOrInsert(
                ['category_id'=>$req->cat_id,'voc_date'=>$req->voc_date,'voc_type'=>$req->voc_type,'voucher_entery'=>0],
                [   
                    'user_id' =>Auth::id(),
                    'category_id' => $req->cat_id,
                    'voc_fund_type' => $req->voc_fund_type,
                    'voc_type' => $req->voc_type,
                    'voc_date' => $req->voc_date,
                    'voc_cash' => 0,
                    'voc_bank' => 0,
                    'voc_json' => json_encode($voc_json),
                    'voc_memo_stk' => 0,
                    'voc_property' => 0,
                    'voucher_entery' => 0
                ]
            );
            session()->flash('success', 'BBF Details Added Successfully!!!');
            return redirect($route);
        }else{
            session()->flash('success', 'Please Try Again Later!!!');
            return redirect($route);
        }
    }

    /**
     * Get Auto BBF Data
     */
    public function getAutoBbfData(Request $request, $cat_id)
    {
        $startOfMonth = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endOfMonth = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $fundfor = $request->get('fund_type', 'both');
        
        // Get payment totals (these are essentially the grand totals for subcategories)
        $paymentTotal = $this->selectedMonthPaymentTotal($cat_id, $fundfor, $startOfMonth, $endOfMonth);
        
        // Get subcategories to map the totals correctly
        $subcategories = Category::where('parent_id', $cat_id)->get(['name']);
        
        $autoBbfData = [
            'voc_cash' => $paymentTotal['voc_cash'] ?? 0,
            'voc_bank' => $paymentTotal['voc_bank'] ?? 0,
            'voc_memo_stk' => $paymentTotal['voc_memo_stk'] ?? 0,
            'voc_property' => $paymentTotal['voc_property'] ?? 0,
            'subcategories' => []
        ];
        
        // Map subcategory totals
        if (isset($paymentTotal['paymenttotal'])) {
            foreach ($subcategories as $subcat) {
                $name = strtolower(preg_replace('/\s+/', '_', trim($subcat->name)));
                $autoBbfData['subcategories'][$name] = $paymentTotal['paymenttotal'][$name] ?? 0;
            }
        }
        
        return response()->json([
            'status' => true,
            'data' => $autoBbfData
        ]);
    }

    public function grandTotal(Request $req)
    {
        $data = DB::table('grand_total')
            ->updateOrInsert(
                ['category_id'=>$req->cat_id,'rdate'=>$req->rdate,'voc_type'=>$req->voc_type],
                ['category_id'=>$req->cat_id,'user_id'=>Auth::id(),'assets'=>$req->assets,'liabilities'=>$req->liabilities,'rdate'=>$req->rdate,'voc_type'=>$req->voc_type,'book_amount'=>$req->book_amount]
        );
        return response()->json(['status'=>true,'data'=>$data]);
    }

    public function totalAllotmentAdd(Request $req)
    {
        // Validate the request
        $req->validate([
            'fund_type' => 'required|string',
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer|exists:categories,id',
            'allotment_amount' => 'required|numeric|min:0',
            'allotment_date' => 'required|date',
            'allotment_description' => 'nullable|string'
        ]);

        try {
            // Get subcategory name for better identification
            $subcategory = Category::find($req->subcategory_id);
            
            // Create total allotment entry in dedicated table
            TotalAllotment::create([
                'user_id' => Auth::id(),
                'category_id' => $req->category_id,
                'subcategory_id' => $req->subcategory_id,
                'fund_type' => $req->fund_type,
                'allotment_amount' => $req->allotment_amount,
                'allotment_date' => $req->allotment_date,
                'description' => $req->allotment_description
            ]);

            return redirect()->back()->with('success', 'Total Allotment for ' . $subcategory->name . ' added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding Total Allotment: ' . $e->getMessage());
        }
    }

    public function totalAllotmentDelete($id)
    {
        try {
            $allotment = TotalAllotment::findOrFail($id);
            $subcategoryName = $allotment->subcategory ? $allotment->subcategory->name : 'Unknown';
            
            $allotment->delete();
            
            return redirect()->back()->with('success', 'Total Allotment for ' . $subcategoryName . ' deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting Total Allotment: ' . $e->getMessage());
        }
    }

    public function pcdaTransactionAdd(Request $req)
    {
        $req->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'fund_type' => 'required|string',
            'transaction_type' => 'required|in:sent_to_pcda,booked_by_pcda',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        try {
            $subcategory = $req->subcategory_id ? Category::find($req->subcategory_id) : null;
            $transactionLabel = $req->transaction_type === 'sent_to_pcda' ? 'Amount Sent to PCDA' : 'Amount Booked by PCDA';
            
            PcdaTransaction::create([
                'user_id' => Auth::id(),
                'category_id' => $req->category_id,
                'subcategory_id' => $req->subcategory_id,
                'fund_type' => $req->fund_type,
                'transaction_type' => $req->transaction_type,
                'amount' => $req->amount,
                'transaction_date' => $req->transaction_date,
                'description' => $req->description
            ]);

            return redirect()->back()->with('success', $transactionLabel . ($subcategory ? ' for ' . $subcategory->name : '') . ' added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error adding PCDA Transaction: ' . $e->getMessage());
        }
    }

    public function pcdaTransactionDelete($id)
    {
        try {
            $transaction = PcdaTransaction::findOrFail($id);
            $subcategoryName = $transaction->subcategory ? $transaction->subcategory->name : 'Unknown';
            $transactionLabel = $transaction->transaction_type === 'sent_to_pcda' ? 'Amount Sent to PCDA' : 'Amount Booked by PCDA';
            
            $transaction->delete();
            
            return redirect()->back()->with('success', $transactionLabel . ' for ' . $subcategoryName . ' deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting PCDA Transaction: ' . $e->getMessage());
        }
    }

    public function merReport(Request $request)
    {
        // Get all main categories to allow selection
        $allCategories = Category::where('parent_id', 0)->get();
        
        // Look for Public Fund category first, then allow user selection
        $publicFundCategory = Category::where('name', 'LIKE', '%Public Fund%')
            ->orWhere('name', 'LIKE', '%Public%')
            ->where('parent_id', 0)
            ->first();
            
        // Get selected category (default to Public Fund or first available)
        $selectedCategoryId = $request->get('category_id', $publicFundCategory?->id ?? $allCategories->first()?->id);
        $publicCategory = Category::where('id', $selectedCategoryId)->where('parent_id', 0)->first();
        
        if (!$publicCategory) {
            return redirect()->route('dashboard')->with('error', 'Selected category not found! Please ensure you have created at least one main category.');
        }

        // Set date filters
        $currentYear = $request->get('year', date('Y'));
        $currentMonth = $request->get('month', date('m'));
        $startOfMonth = "$currentYear-$currentMonth-01";
        $endOfMonth = date('Y-m-t', strtotime($startOfMonth));
        $startOfYear = "$currentYear-04-01"; // Financial year starts in April

        // Get all subcategories under selected category
        $subcategories = Category::where('parent_id', $publicCategory->id)->get();
        
        // Get all active units
        $units = Unit::where('is_active', true)->get();
        $unitName = $units->count() > 0 ? $units->first()->unit_name : 'ALL UNITS';
        
        $merData = [];
        $serCounter = 1;

        // Get expenditure up to previous month for all subcategories at once
        $prevMonthEnd = date('Y-m-t', strtotime($startOfMonth . ' -1 month'));
        $expdUpToPrevData = $this->getSubcategoryExpenditureTotal($publicCategory->id, $startOfYear, $prevMonthEnd);
        
        // Get expenditure during current month for all subcategories at once
        $expdDuringMonthData = $this->getSubcategoryExpenditureTotal($publicCategory->id, $startOfMonth, $endOfMonth);

        foreach ($subcategories as $subcategory) {
            // Get Total Allotment for this subcategory
            $totalAllotment = TotalAllotment::where('category_id', $publicCategory->id)
                ->where('subcategory_id', $subcategory->id)
                ->where('fund_type', $publicCategory->name)
                ->sum('allotment_amount');

            // Get subcategory key for accessing the expenditure array
            $subcategoryKey = strtolower(str_replace(" ", "_", $subcategory->name));
            
            // Get expenditure up to previous month
            $expdUpToPrev = $expdUpToPrevData[$subcategoryKey] ?? 0;

            // Get expenditure during current month
            $expdDuringMonth = $expdDuringMonthData[$subcategoryKey] ?? 0;

            // Calculate total expenditure
            $totalExpd = $expdUpToPrev + $expdDuringMonth;

            // Calculate percentage of expenditure
            $percentageExpd = $totalAllotment > 0 ? ($totalExpd / $totalAllotment) * 100 : 0;

            // Get Bills forwarded to PCDA for this subcategory (aggregate across all units)
            $billsFwdToPcda = PcdaTransaction::where('category_id', $publicCategory->id)
                ->where('subcategory_id', $subcategory->id)
                ->where('transaction_type', 'sent_to_pcda')
                ->where('fund_type', $publicCategory->name)
                ->where('transaction_date', '>=', $startOfYear)
                ->sum('amount');

            // Get Amount booked by PCDA for this subcategory (aggregate across all units)
            $amtBookedByPcda = PcdaTransaction::where('category_id', $publicCategory->id)
                ->where('subcategory_id', $subcategory->id)
                ->where('transaction_type', 'booked_by_pcda')
                ->where('fund_type', $publicCategory->name)
                ->where('transaction_date', '>=', $startOfYear)
                ->sum('amount');

            // Calculate percentage of booking: (Amount booked by PCDA / Bills fwd to PCDA) * 100
            $percentageBooking = $billsFwdToPcda > 0 ? ($amtBookedByPcda / $billsFwdToPcda) * 100 : 0;

            // Calculate balance
            $balance = $totalAllotment - $totalExpd;

            // Always add all subcategories to show complete structure, even with zero values
            $merData[] = [
                'ser' => $serCounter++,
                'unit' => $unitName,
                'budget_head' => $subcategory->name,
                'total_allotment' => $totalAllotment,
                'expd_upto_prev' => $expdUpToPrev,
                'expd_during_month' => $expdDuringMonth,
                'total_expd' => $totalExpd,
                'percentage_expd' => round($percentageExpd, 2),
                'bills_fwd_pcda' => $billsFwdToPcda,
                'amt_booked_pcda' => $amtBookedByPcda,
                'percentage_booking' => round($percentageBooking, 2),
                'balance' => $balance,
            ];
        }

        $title = 'Monthly Expenditure Return (MER) - ' . date('F Y', strtotime($startOfMonth));
        
        return view('reports.mer', compact('title', 'merData', 'currentYear', 'currentMonth', 'publicCategory', 'allCategories', 'selectedCategoryId', 'units', 'subcategories', 'unitName'));
    }

    /**
     * Get subcategory-wise expenditure totals from payments (not balance)
     */
    private function getSubcategoryExpenditureTotal($categoryId, $startDate, $endDate)
    {
        // Get all payment vouchers for the category in the date range
        $vouchers = Voucher::select('voc_json')
            ->where('category_id', $categoryId)
            ->where('voc_type', 'Payment')
            ->whereBetween('voc_date', [$startDate, $endDate])
            ->get();

        $expenditureData = [];

        foreach ($vouchers as $voucher) {
            if ($voucher->voc_json) {
                $voc_json = \App\Helpers\Helper::categories_voc_json($voucher->voc_json, $categoryId);
                foreach ($voc_json as $key => $value) {
                    if ($key != '' && $value !== null && is_numeric($value)) {
                        $expenditureData[$key] = ($expenditureData[$key] ?? 0) + (float)$value;
                    }
                }
            }
        }

        return $expenditureData;
    }

    public function qabReport(Request $request)
    {
        try {
            // Get all main categories for the report
            $allCategories = Category::where('parent_id', 0)->get();
            
            // Date and quarter calculations
            $currentYear = (int) $request->get('year', date('Y'));
            $selectedQuarter = (int) $request->get('quarter', $this->getCurrentQuarter());
            
            // Calculate current quarter based on current date
            $currentQuarter = $this->getCurrentQuarter();
            
            // Calculate previous quarter
            $previousQuarter = $selectedQuarter == 1 ? 4 : $selectedQuarter - 1;
            $previousYear = $selectedQuarter == 1 ? $currentYear - 1 : $currentYear;
            // Get quarter dates
            $currentQuarterDates = $this->getQuarterDates($currentYear, $selectedQuarter);
            $previousQuarterDates = $this->getQuarterDates($previousYear, $previousQuarter);

            // Fetch QAB data for ALL categories
            $qabData = [];
            
            // Loop through all main categories and calculate their cash balances
            foreach($allCategories as $category) {
                // Use selectedMonthPaymentBalance to get cash and bank amounts combined
                $currentBalance = $this->selectedMonthPaymentBalance($category->id, 'both', $currentQuarterDates['start'], $currentQuarterDates['end']);
                $previousBalance = $this->selectedMonthPaymentBalance($category->id, 'both', $previousQuarterDates['start'], $previousQuarterDates['end']);
                
                // Separate cash hand and bank from the balance
                $cashHandCurrent = $currentBalance['voc_cash'] ?? 0;
                $cashBankCurrent = $currentBalance['voc_bank'] ?? 0;
                $cashHandPrevious = $previousBalance['voc_cash'] ?? 0;
                $cashBankPrevious = $previousBalance['voc_bank'] ?? 0;
                
                // Debug log for category ID 1
                if($category->id == 1) {
                    \Log::info('QAB Debug for Category 1:', [
                        'category_name' => $category->name,
                        'cash_hand_current' => $cashHandCurrent,
                        'cash_bank_current' => $cashBankCurrent,
                        'current_quarter_end' => $currentQuarterDates['end']
                    ]);
                }
                
                $qabData[] = [
                    'account_name' => $category->name,
                    'current_cash_hand' => $cashHandCurrent,
                    'current_cash_bank' => $cashBankCurrent,
                    'previous_cash_hand' => $cashHandPrevious,
                    'previous_cash_bank' => $cashBankPrevious,
                    'diff_cash_hand' => $cashHandCurrent - $cashHandPrevious,
                    'diff_cash_bank' => $cashBankCurrent - $cashBankPrevious,
                    'reason' => $this->getReasonForVariation($category->name)
                ];
            }

            $title = 'Quarterly Audit Board (QAB) Report';
            
            return view('reports.qab', compact(
                'title', 'qabData', 'currentYear', 'selectedQuarter', 
                'allCategories', 'currentQuarter', 'previousQuarter', 'previousYear'
            ));
            
        } catch (\Exception $e) {
            \Log::error('QAB Report Error: ' . $e->getMessage());
            return response('QAB Report Error: ' . $e->getMessage(), 500);
        }
    }

    // Helper methods for QAB report
    private function getQuarterDates($year, $quarter)
    {
        switch ($quarter) {
            case 1: // Q1: Apr-Jun
                return [
                    'start' => "$year-04-01",
                    'end' => "$year-06-30"
                ];
            case 2: // Q2: Jul-Sep
                return [
                    'start' => "$year-07-01",
                    'end' => "$year-09-30"
                ];
            case 3: // Q3: Oct-Dec
                return [
                    'start' => "$year-10-01",
                    'end' => "$year-12-31"
                ];
            case 4: // Q4: Jan-Mar
                $nextYear = $year + 1;
                return [
                    'start' => "$nextYear-01-01",
                    'end' => "$nextYear-03-31"
                ];
            default:
                return [
                    'start' => "$year-04-01",
                    'end' => "$year-06-30"
                ];
        }
    }

    private function getCurrentQuarter()
    {
        $currentMonth = (int) date('m');
        if ($currentMonth >= 4 && $currentMonth <= 6) return 1; // Q1: Apr-Jun
        if ($currentMonth >= 7 && $currentMonth <= 9) return 2; // Q2: Jul-Sep
        if ($currentMonth >= 10 && $currentMonth <= 12) return 3; // Q3: Oct-Dec
        return 4; // Q4: Jan-Mar
    }

    private function getQuarterBalance($categoryId, $subcategoryId, $endDate)
    {
        // Get total allotment balance for the subcategory up to end date
        $totalAllotment = TotalAllotment::where('category_id', $categoryId)
            ->where('subcategory_id', $subcategoryId)
            ->whereDate('allotment_date', '<=', $endDate)
            ->sum('allotment_amount');

        // Note: Vouchers table doesn't have subcategory_id, so we aggregate by main category
        // This gives us the total expenditure for the main category
        // You might need to modify this logic based on your business rules
        $totalExpenditure = Voucher::where('category_id', $categoryId)
            ->whereDate('voc_date', '<=', $endDate)
            ->sum(DB::raw('COALESCE(CAST(voc_cash as DECIMAL(10,2)), 0) + COALESCE(CAST(voc_bank as DECIMAL(10,2)), 0)'));

        // For subcategory balance calculation, we'll use only allotment
        // since vouchers don't track subcategory-wise expenditure
        return $totalAllotment;
    }

    private function getCashBalance($categoryId, $cashType, $endDate)
    {
        // Replicate the exact BBF calculation logic for historical dates
        // Calculate from financial year start to end date
        $endYear = date('Y', strtotime($endDate));
        $endMonth = date('n', strtotime($endDate));
        
        // Determine financial year start
        if ($endMonth >= 4) {
            $startDate = $endYear . '-04-01';
        } else {
            $startDate = ($endYear - 1) . '-04-01';
        }
        
        // Get receipts up to end date
        $receipts = Voucher::where('category_id', $categoryId)
            ->where('voc_type', 'Receipt')
            ->whereBetween('voc_date', [$startDate, $endDate])
            ->sum(DB::raw($cashType == 'hand' ? 'COALESCE(CAST(voc_cash as DECIMAL(10,2)), 0)' : 'COALESCE(CAST(voc_bank as DECIMAL(10,2)), 0)'));
            
        // Get payments up to end date
        $payments = Voucher::where('category_id', $categoryId)
            ->where('voc_type', 'Payment')
            ->whereBetween('voc_date', [$startDate, $endDate])
            ->sum(DB::raw($cashType == 'hand' ? 'COALESCE(CAST(voc_cash as DECIMAL(10,2)), 0)' : 'COALESCE(CAST(voc_bank as DECIMAL(10,2)), 0)'));
            
        // Get the latest BBF entry for this category up to the end date
        $latestBBF = VoucherBBF::where('category_id', $categoryId)
            ->whereDate('voc_date', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->first();
            
        $bbfAmount = 0;
        if ($latestBBF) {
            // Get the appropriate amount (cash or bank) from the latest BBF entry
            $bbfCashAmount = (float) ($latestBBF->voc_cash ?? 0);
            $bbfBankAmount = (float) ($latestBBF->voc_bank ?? 0);
            
            if ($cashType == 'hand') {
                // For Receipt type BBF, add cash amount; for Payment type BBF, subtract cash amount
                $bbfAmount = ($latestBBF->voc_type === 'Receipt') ? $bbfCashAmount : -$bbfCashAmount;
            } else {
                // For Receipt type BBF, add bank amount; for Payment type BBF, subtract bank amount  
                $bbfAmount = ($latestBBF->voc_type === 'Receipt') ? $bbfBankAmount : -$bbfBankAmount;
            }
        }
            
        // Apply BBF logic: Receipts - Payments + Latest_BBF_Amount
        return ($receipts - $payments + $bbfAmount);
    }

    private function getBBFBalance($categoryId, $voc_type, $endDate)
    {
        // Get the latest BBF entry for this category up to the end date (regardless of type)
        $latestBBF = VoucherBBF::select('voc_cash', 'voc_bank', 'voc_type')
            ->where('category_id', $categoryId)
            ->whereDate('voc_date', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($latestBBF && $latestBBF->voc_type === $voc_type) {
            return [
                'voc_cash' => $latestBBF->voc_cash ?? 0,
                'voc_bank' => $latestBBF->voc_bank ?? 0
            ];
        }
        
        return [
            'voc_cash' => 0,
            'voc_bank' => 0
        ];
    }

    private function getQuarterName($quarter)
    {
        $names = [1 => 'Q1', 2 => 'Q2', 3 => 'Q3', 4 => 'Q4'];
        return $names[$quarter] ?? 'Q1';
    }

    private function getQuarterEndMonth($quarter)
    {
        $months = [1 => 'JUN', 2 => 'SEP', 3 => 'DEC', 4 => 'MAR'];
        return $months[$quarter] ?? 'JUN';
    }

    private function getSubcategoryCashBalance($categoryId, $subcategoryId, $cashType, $endDate)
    {
        // Since vouchers don't have subcategory_id, we'll calculate based on allotments
        // This is a simplified approach - you might need to adjust based on your business logic
        $allotmentAmount = TotalAllotment::where('category_id', $categoryId)
            ->where('subcategory_id', $subcategoryId)
            ->whereDate('allotment_date', '<=', $endDate)
            ->sum('allotment_amount');
            
        // Return a percentage of allotment as cash balance (adjust as needed)
        if ($cashType == 'hand') {
            return $allotmentAmount * 0.1; // 10% as cash in hand
        } else {
            return $allotmentAmount * 0.9; // 90% as cash in bank
        }
    }

    private function getReasonForVariation($accountName)
    {
        $reasons = [
            'Regt Fund' => 'Due to receipt of Regt Chilling.',
            'Offrs Mess' => 'Due to receipt of mess bills.',
            'JCOs Mess' => 'Bills to mess stock.',
            'ORs Mess' => 'Due to misc stock.',
            'CSB Acct' => 'Due to Sale of Grocery Liquor Stores.'
        ];
        
        return $reasons[$accountName] ?? 'Due to receipt of various grants.';
    }

    private function getVoucherPageBalance($categoryId, $subcategoryName, $endDate)
    {
        // Use the exact same date range as voucher page for Quarter 2
        // Quarter 2 is July-September, so use quarter start to quarter end
        $endYear = date('Y', strtotime($endDate));
        $endMonth = date('n', strtotime($endDate));
        
        // For Quarter 2 (July-Sept), use the quarter dates directly
        if ($endMonth >= 7 && $endMonth <= 9) {
            $startDate = $endYear . '-07-01';
            $endDate = $endYear . '-09-30';
        } else if ($endMonth >= 4 && $endMonth <= 6) {
            // Q1: Apr-Jun
            $startDate = $endYear . '-04-01';
            $endDate = $endYear . '-06-30';
        } else if ($endMonth >= 10 && $endMonth <= 12) {
            // Q3: Oct-Dec
            $startDate = $endYear . '-10-01';
            $endDate = $endYear . '-12-31';
        } else {
            // Q4: Jan-Mar (next year)
            $startDate = $endYear . '-01-01';
            $endDate = $endYear . '-03-31';
        }
        
        // Create subcategory key (convert to lowercase with underscores)
        $subcatKey = strtolower(str_replace([' ', '-'], '_', $subcategoryName));
        
        // Get balance using the exact same method and date range as voucher page
        $paymentBalance = $this->selectedMonthPaymentBalance($categoryId, 'both', $startDate, $endDate);
        
        // Return the subcategory balance if it exists
        if (isset($paymentBalance['bfftotal'][$subcatKey])) {
            return $paymentBalance['bfftotal'][$subcatKey];
        }
        
        return 0;
    }
    
    private function getVoucherPageBalanceForQuarter($categoryId, $subcategoryName, $startDate, $endDate)
    {
        // Create subcategory key (convert to lowercase with underscores)
        $subcatKey = strtolower(str_replace([' ', '-'], '_', $subcategoryName));
        
        // Get balance using the exact same method and date range as voucher page
        $paymentBalance = $this->selectedMonthPaymentBalance($categoryId, 'both', $startDate, $endDate);
        
        // Return the subcategory balance if it exists
        if (isset($paymentBalance['bfftotal'][$subcatKey])) {
            return $paymentBalance['bfftotal'][$subcatKey];
        }
        
        return 0;
    }

    public function qabCategoryReport(Request $request)
    {
        try {
            // Get all main categories for dropdown
            $allCategories = Category::where('parent_id', 0)->get();
            
            // Get selected category (default to first available)
            $selectedCategoryId = $request->get('category_id', $allCategories->first()?->id);
            $selectedCategory = Category::where('id', $selectedCategoryId)->where('parent_id', 0)->first();
            
            if (!$selectedCategory) {
                return redirect()->route('qab-report')->with('error', 'Category not found');
            }
            
            // Date and quarter calculations
            $currentYear = (int) $request->get('year', date('Y'));
            $selectedQuarter = (int) $request->get('quarter', $this->getCurrentQuarter());
            $currentQuarter = $this->getCurrentQuarter();
            
            // Calculate previous quarter within the same financial year
            if ($selectedQuarter == 1) {
                // Q1 (Apr-Jun) compares with Q4 of previous financial year
                $previousQuarter = 4;
                $previousYear = $currentYear - 1;
            } else {
                // Q2, Q3, Q4 compare with previous quarter in same financial year
                $previousQuarter = $selectedQuarter - 1;
                $previousYear = $currentYear;
            }
            
            // Get quarter dates
            $currentQuarterDates = $this->getQuarterDates($currentYear, $selectedQuarter);
            $previousQuarterDates = $this->getQuarterDates($previousYear, $previousQuarter);

            // Get subcategories for the selected main category
            $subcategories = Category::where('parent_id', $selectedCategory->id)->get();
            
            // Prepare QAB category data
            $qabCategoryData = [];
            
            // Add Cash In Hand - using voucher page calculation with exact quarter dates
            $voucherPageBalance = $this->selectedMonthPaymentBalance($selectedCategory->id, 'both', $currentQuarterDates['start'], $currentQuarterDates['end']);
            $voucherPageBalancePrev = $this->selectedMonthPaymentBalance($selectedCategory->id, 'both', $previousQuarterDates['start'], $previousQuarterDates['end']);
            
            $cashHandCurrent = $voucherPageBalance['voc_cash'];
            $cashHandPrevious = $voucherPageBalancePrev['voc_cash'];
            
            $qabCategoryData[] = [
                'fund_head' => 'CASH IN HAND',
                'current_balance' => $cashHandCurrent,
                'previous_balance' => $cashHandPrevious,
                'difference' => $cashHandCurrent - $cashHandPrevious,
                'remarks' => $cashHandCurrent == 0 && $cashHandPrevious == 0 ? '-' : 'Due to receipt of various grants.'
            ];
            
            // Add Cash In Bank - using voucher page calculation with exact quarter dates
            $cashBankCurrent = $voucherPageBalance['voc_bank'];
            $cashBankPrevious = $voucherPageBalancePrev['voc_bank'];
            
            $qabCategoryData[] = [
                'fund_head' => 'CASH IN BANK',
                'current_balance' => $cashBankCurrent,
                'previous_balance' => $cashBankPrevious,
                'difference' => $cashBankCurrent - $cashBankPrevious,
                'remarks' => $cashBankCurrent == 0 && $cashBankPrevious == 0 ? '-' : 'Due to receipt of various grants.'
            ];
            
            // Add subcategories using the same logic as voucher page with exact quarter dates
            foreach($subcategories as $subcat) {
                // Get balances using the exact same quarter date range as voucher page
                $currentBalance = $this->getVoucherPageBalanceForQuarter($selectedCategory->id, $subcat->name, $currentQuarterDates['start'], $currentQuarterDates['end']);
                $previousBalance = $this->getVoucherPageBalanceForQuarter($selectedCategory->id, $subcat->name, $previousQuarterDates['start'], $previousQuarterDates['end']);
                
                $qabCategoryData[] = [
                    'fund_head' => strtoupper($subcat->name),
                    'current_balance' => $currentBalance,
                    'previous_balance' => $previousBalance,
                    'difference' => $currentBalance - $previousBalance,
                    'remarks' => $currentBalance == 0 && $previousBalance == 0 ? '-' : 'Due to almt & expdr'
                ];
            }

            $title = 'QAB Category Report - ' . $selectedCategory->name;
            
            return view('reports.qab-category', compact(
                'title', 'qabCategoryData', 'currentYear', 'selectedQuarter', 'selectedCategory',
                'allCategories', 'selectedCategoryId', 'currentQuarter'
            ));
            
        } catch (\Exception $e) {
            \Log::error('QAB Category Report Error: ' . $e->getMessage());
            return response('QAB Category Report Error: ' . $e->getMessage(), 500);
        }
    }

    public function debugVouchers()
    {
        $vouchers = Voucher::with('category')->get();
        $categories = Category::where('parent_id', 0)->get();
        
        $debug = [
            'total_vouchers' => $vouchers->count(),
            'categories' => $categories->pluck('name', 'id')->toArray(),
            'vouchers' => $vouchers->map(function($v) {
                return [
                    'id' => $v->id,
                    'category_id' => $v->category_id,
                    'category_name' => $v->category ? $v->category->name : 'Unknown',
                    'date' => $v->voc_date,
                    'cash' => $v->voc_cash,
                    'bank' => $v->voc_bank,
                    'json' => $v->voc_json
                ];
            })->toArray()
        ];
        
        return response()->json($debug);
    }
}
