<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Officers;
use App\Models\Category;
use App\Models\Messbill;
use App\Models\MessbillSubCategory;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use Barryvdh\DomPDF\Facade\Pdf;

class MessBillSummaryController extends Controller
{
    public function messBillList(Request $req,$cat_id)
    {
        if(isset($req->start_date) && isset($req->end_date)){
            $startOfMonth = $req->start_date;
            $endOfMonth = $req->end_date;
        }else{
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
        }
        $id = Auth::user()->id;
        $combine = $result = [];
        $roff = 0;
        $subcategories = Category::where('parent_id',$cat_id)->get(['id','name']);
        $subcats = MessbillSubCategory::where([
                'category_id'=>$cat_id,
                'subcat_date'=>$startOfMonth
            ])->get(['main_category','subcategory_name','amount']);
        $officerlist = Officers::where('category_id',$cat_id)->get();
        $drodownofficecheck = Messbill::select('officer_id')
        ->where(['category_id'=>$cat_id])
        ->whereBetween('bill_date', [$startOfMonth,$endOfMonth])
        ->get();
        foreach($subcategories as $subs){
            $main = strtolower(str_replace(" ","_",$subs->name));
            $combine[$main] = '';
            foreach($subcats as $key=>$subs){
                if($subs->main_category == $main){
                    $combine[strtolower(str_replace(" ","_",$subs->subcategory_name))] = '';
                }
            }
        }
        // print_r($combine);
        $messbill = Messbill::where('category_id',$cat_id)->whereBetween('bill_date',[$startOfMonth,$endOfMonth])->get();
        $perday = 0;
        $other = [];
        if(count($messbill)>0){
            foreach($messbill as $mess)
            {
                $perday+=$mess->no_of_days*$mess->per_day_messing;
                $roff+=$mess->round_off;
                $bills = json_decode($mess->bill_json,true);
                foreach ($bills as $key=>$value){
                    if (!isset($sums[$key])){
                        $sums[$key] = 0;
                        $forother[$key] = 0;
                    }
                    $sums[$key] += $value; 
                    $forother[$key] += $value; 
                }
                $subcatgs_json = json_decode($mess->subcatgs_json,true);
                foreach ($subcatgs_json as $key1=>$value){
                    foreach($value as $key=>$value){
                        if (!isset($sums[$key])){
                            $sums[$key] = 0;
                            $other[$key1][$key] = 0;
                        }
                        $sums[$key] += $value; 
                        $other[$key1][$key] += $value; 
                    }
                }
            }
            // print_r($forother);
            foreach ($other as $key=>$sub_array){
                if (is_array($sub_array)){
                    if($key=='cat_stock'){
                        $other[$key] = array_sum($sub_array)+$perday;
                    }else{
                        $other[$key] = array_sum($sub_array);
                    }                
                }
            }
            // print_r($other);            
            foreach ($forother as $key=>$value){
                if (isset($other[$key])){
                    $result[$key] = Helper::numberFormat($value + $other[$key]); // Sum of matching keys
                }else{
                    if($key=='ent_fund'){
                        $result[$key] = Helper::numberFormat($value+$roff);
                    }else{
                        $result[$key] = Helper::numberFormat($value);
                    }
                }
            }
        }else{
            foreach ($subcategories as $name){
                $sums[strtolower(str_replace(" ", "_", $name->name))] = null; // Set null or a default value
                $result[strtolower(str_replace(" ", "_", $name->name))] = Helper::numberFormat(0); // Set null or a default value
            }
        }
        
        // print_r($result);
        // die;
        $sortedArray = [];
        foreach ($combine as $key=>$value) {
            if (isset($sums[$key])){
                $sortedArray[$key] = $sums[$key];
            }
        }
        // print_r($sortedArray);echo array_sum($sortedArray);die;
        
        DB::table('vouchers_bbf')
            ->updateOrInsert(
                ['voc_fund_type' => 'Mess Bill', 'voc_date' => $startOfMonth,'voc_type'=>'Messbill'],
                ['category_id'=>$cat_id,'voc_fund_type'=>'Mess Bill','voc_type'=>'Messbill','voc_date'=>$startOfMonth,'voc_cash'=>0,'voc_bank'=>0,'voc_json'=>json_encode($result)]
            );
        $categories = Category::where('parent_id',$cat_id)->get(['name','type']);
        $title = 'Mess Bill Summary';
        return view('messbill.messbillsummarylist',compact('officerlist','title','categories','messbill','subcategories','subcats','sortedArray','drodownofficecheck','result'));
    }

    public function messbillAdd(Request $req)
    {
        // return $req->all();
        $billtotal=$catbill=$subcat=0;
        foreach($req->catbill as $key=>$value){
            $catbill+=$value;
        }
        // foreach($req->subcatgs as $key=>$value){
            //     foreach($value as $key=>$value){
            //         $subcat+=$value;
            //     }
            // }
        $subcats = MessbillSubCategory::where([
                    'category_id'=>$req->category_id,
                    'subcat_date'=>$req->sub_cat_date,            
                ])->get(['main_category','subcategory_name','amount']);
        $sub_no_of = $subcatgs = [];
        if(count($subcats)>0){
            foreach($subcats as $key=>$value)
            {
                if($value['amount']!=''){
                    $sub_no_of[$value['main_category']][strtolower(str_replace(" ", "_",$value['subcategory_name']))] = round($req->no_of_days * $value['amount'],2);
                }
            }
            // return $req->subcatgs;
            // return $sub_no_of;
            // print_r($req->subcatgs);
            // print_r($sub_no_of);
            $subcatgs = array_replace_recursive($req->subcatgs,$sub_no_of);
            // die;
            foreach($subcatgs as $key=>$value){
                foreach($value as $key=>$value){
                    $subcat+=$value;
                }
            }
        }else{
            $subcat = 0;
        }
        $billtotal = $catbill+$subcat+$req->no_of_days*$req->per_day_messing;
        $round_off = round($billtotal)-$billtotal;
        $round_off = round($round_off,2);
        $messbill = new Messbill();
        $messbill->category_id=$req->category_id;
        $messbill->user_id=Auth::id();
        $messbill->officer_id=$req->officer_id;
        if($req->officer_id=='other'){
            $messbill->officer_rank=$req->other_officer_rank;
            $messbill->officer_name=$req->other_officer_name;
        }else{
            $messbill->officer_rank=$req->officer_rank;
            $messbill->officer_name=$req->officer_name;
        }
        $messbill->no_of_days=$req->no_of_days;
        $messbill->per_day_messing=$req->per_day_messing;
        $messbill->arrears=$req->arrears;
        $messbill->round_off=$round_off;
        $messbill->bill_date=$req->bill_date;
        $messbill->bill_json=json_encode($req->catbill);
        $messbill->subcatgs_json=json_encode($subcatgs);
        $messbill->bill_total=$billtotal;
        if ($messbill->save()){
            session()->flash('success', 'Details Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }
    }

    public function messbillEdit($offid,$catid)
    {
        $officerlist='';
        $title = 'Mess Bill Summary Edit';
        $messbill = Messbill::where('id',$offid)->first();
        $officerlist = Officers::where('id',$offid)->first();
        $categories = Category::where('parent_id',$catid)->get(['name','type']);
        $subcats = MessbillSubCategory::get(['main_category','subcategory_name']);
        return view('messbill.messbillsummaryedit',compact('officerlist','title','categories','catid','messbill','subcats'));
    }

    public function messbillUpdate(Request $req)
    {
        $messbill = Messbill::find($req->id);
        $route = "messbilllist/".$req->category_id."?start_date=".$req->start."&end_date=".$req->end;
        if($messbill){
            $billtotal=$catbill=$subcat=0;
            foreach($req->catbill as $key=>$value){
                $catbill+=$value;
            }
            // foreach($req->subcatgs as $key=>$value){
            //     foreach($value as $key=>$value){
            //         $subcat+=$value;
            //     }
            // }
            $subcats = MessbillSubCategory::where([
                        'category_id'=>$req->category_id,
                        'subcat_date'=>$req->start,            
                    ])->get(['main_category','subcategory_name','amount']);
            $sub_no_of = [];
            foreach($subcats as $key=>$value)
            {
                if($value['amount']!=''){
                    $sub_no_of[$value['main_category']][strtolower(str_replace(" ", "_",$value['subcategory_name']))] = round($req->no_of_days * $value['amount'],2);
                }
            }
            // return $req->subcatgs;
            // return $sub_no_of;
            // print_r($req->subcatgs);
            // print_r($sub_no_of);
            $subcatgs = array_replace_recursive($req->subcatgs,$sub_no_of);
            // die;
            foreach($subcatgs as $key=>$value){
                foreach($value as $key=>$value){
                    $subcat+=$value;
                }
            }
            $billtotal = $catbill+$subcat;
            $round_off = round($billtotal)-$billtotal;
            $round_off = round($round_off,2);
            $messbill->category_id=$req->category_id;
            $messbill->user_id=Auth::id();
            $messbill->officer_id=$req->officer_id;
            $messbill->officer_rank=$req->officer_rank;
            $messbill->officer_name=$req->officer_name;
            $messbill->no_of_days=$req->no_of_days;
            $messbill->per_day_messing=$req->per_day_messing;
            $messbill->arrears=$req->arrears;
            $messbill->round_off=$round_off;
            $messbill->bill_date=$req->bill_date;
            $messbill->bill_json=json_encode($req->catbill);
            $messbill->subcatgs_json=json_encode($subcatgs);
            $messbill->bill_total=$billtotal;
            if ($messbill->save()){
                session()->flash('success', 'Details Updated Successfully!!!');
                return redirect($route);
            }else{
                session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
                return redirect($route);
            }
        }else{
            session()->flash('error', 'ID Not Found!!!');
            return redirect($route);
        }
    }

    public function messbillDelete($id,$catid,$start,$end)
    {
        $messbill = Messbill::find($id);
        $route = "messbilllist/".$catid."?start_date=".$start."&end_date=".$end;
        if($messbill->delete()){
            session()->flash('success', 'Messbill Summary Deleted Successfully!!!');
            return redirect($route);
        }else{
            session()->flash('error', 'Messbill Summary Not Found!!!');
            return redirect($route);
        }
    }

    public function officerMessbillPdf($mess_id,$catid,$start,$end)
    {
        // $messbill = Messbill::find($mess_id);
        $title = "Officer Messbill Pdf";
        $messbill = Messbill::where('id',$mess_id)->first();
        $bills = json_decode($messbill->bill_json,true);
        foreach ($bills as $key=>$value){
            if (!isset($sums[$key])){
                $sums[$key] = 0;
            }
            $sums[$key] += $value;  
        }
        $subcatgs_json = json_decode($messbill->subcatgs_json,true);
        foreach ($subcatgs_json as $key1=>$value){
            foreach($value as $key=>$value){
                if (!isset($sums[$key])){
                    $sums[$key] = 0;
                }
                $sums[$key] += $value; 
            }
        }
        // print_r($sums);die;
        $pdf = PDF::loadView('messbill.officermessbillpdf',['title'=>$title,'messbill'=>$messbill,'sums'=>$sums]);
        $date = date('d-m-Y_H:i:s');
        return $pdf->download('Officer Messbill-'.$date.'.pdf');
        // return view('messbill.officermessbillpdf',compact('title','messbill','sums'));
    }
}
