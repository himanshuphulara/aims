<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\TotalAllotment;
use App\Models\PcdaTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $title = 'Dashboard';
        $categories = Category::where('parent_id',0)->get('name');
        $colors = ['#438a7a','#477bf9','#50b0f3','#ed4343','#0f0f12','#934fb0'];
        $i=0;
        $colorCat = [];
        foreach($categories as $cat){
            $colorCat[$cat->name] = $colors[$i<count($colors)?$i++:0];
        }
        
        // Check if grand_total table exists
        try {
            $tableExists = DB::select("SHOW TABLES LIKE 'grand_total'");
            if (empty($tableExists)) {
                // Table doesn't exist, use default values
                $assets = array_fill(0, 12, 0);
                $liabilities = array_fill(0, 12, 0);
                $yearly_assets = 0;
                $yearly_liabilities = 0;
                $pieNetKey = [];
                $pieNetValue = [];
                $fundsNetWorth = collect();
                $monthwise = [];
                $threemonthwise = [];
                $yearwise = [];
                $pies = collect();
                $publicFundData = [];
                
                return view('/dashboard',compact('title','assets','liabilities','yearly_assets','yearly_liabilities','pieNetKey','pieNetValue','fundsNetWorth','categories','monthwise','threemonthwise','yearwise','colorCat','pies','publicFundData'));
            }
        } catch (\Exception $e) {
            // Handle any database errors gracefully
            $assets = array_fill(0, 12, 0);
            $liabilities = array_fill(0, 12, 0);
            $yearly_assets = 0;
            $yearly_liabilities = 0;
            $pieNetKey = [];
            $pieNetValue = [];
            $fundsNetWorth = collect();
            $monthwise = [];
            $threemonthwise = [];
            $yearwise = [];
            $pies = collect();
            $publicFundData = [];
            
            return view('/dashboard',compact('title','assets','liabilities','yearly_assets','yearly_liabilities','pieNetKey','pieNetValue','fundsNetWorth','categories','monthwise','threemonthwise','yearwise','colorCat','pies','publicFundData'));
        }
        
        // print_r($colorCat);
        //For Line Graph
        $monthly = DB::table('grand_total')
                ->select(DB::raw('MONTH(rdate) AS month'),DB::raw('SUM(assets) as assets'),DB::raw('SUM(liabilities) as liabilities'))
                ->whereYear('rdate',date('Y'))
                ->groupBy('month')
                ->get();
        
        for($month=1;$month<=12;$month++)
        {
            $assetsm[$month] = optional($monthly->first(function ($row)use($month) { return $row->month == $month; }))->assets??0;
            $liabilitiesm[$month] = optional($monthly->first(function ($row)use($month) { return $row->month == $month; }))->liabilities??0;
        }
        // print_r($assetsm);
        // print_r($liabilitiesm);
        $assets = array_values($assetsm);
        $liabilities = array_values($liabilitiesm);

        //For Pie Graph
        $yearly = $this->pieGraph();
        $yearly_assets = $yearly->sum('assets');
        $yearly_liabilities = $yearly->sum('liabilities');
        
        //For Pie Graph By Funds
        // SELECT category_id,sum(assets),SUM(liabilities),year(rdate) FROM `grand_total` WHERE year(rdate)=2024 GROUP BY category_id,Year(rdate);
        $fundsNetWorth = DB::table('grand_total')
                        ->selectRaw('category_id,categories.name,sum(assets) as assets,SUM(liabilities) as liabilities')
                        ->join('categories', 'categories.id', '=', 'grand_total.category_id')
                        ->whereYear('rdate',date('Y'))
                        ->groupBy('category_id')
                        ->get();
        $pieNetKeys=$pieNetValues=[];
        foreach($fundsNetWorth as $net)
        {
            $pieNetKeys[] = $net->name;
            $pieNetValues[] = abs($net->assets-$net->liabilities);
        }
        // print_r($fundsNetWorth);
        // $pieNetKey = array_values($pieNetKeys);
        // $pieNetValue = array_values($pieNetValues);
        $monthwise = $this->monthly();
        $threemonthwise = $this->threeMonth();
        $yearwise = $this->yearly();
        // print_r($monthwise);
        // print_r($threemonthwise);
        // print_r($yearwise);
        $pies = DB::table('grand_total')
                ->selectRaw('category_id,categories.name,book_amount,rdate')
                ->join('categories', 'categories.id', '=', 'grand_total.category_id')
                ->where('rdate','2024-10-01')
                ->whereYear('rdate',date('Y'))
                ->get();
        // print_r($pies);
        $pieNetKeys=$pieNetValues=[];
        foreach($pies as $net)
        {
            $pieNetKeys[] = $net->name;
            $pieNetValues[] = abs($net->book_amount);
        }
        // print_r($fundsNetWorth);
        $pieNetKey = array_values($pieNetKeys);
        $pieNetValue = array_values($pieNetValues);
        
        // Get Public Fund financial summary for chart
        $publicFundData = $this->getPublicFundSummary();
        
        return view('/dashboard',compact('title','assets','liabilities','yearly_assets','yearly_liabilities','pieNetKey','pieNetValue','fundsNetWorth','categories','monthwise','threemonthwise','yearwise','colorCat','pies','publicFundData'));
    }

    public function pieGraph()
    {
        $start = '2024-10-01';
        $end = '2024-11-30';
        try {
            return DB::table('grand_total')->where('rdate','>=',$start)->where('rdate','<=',$end)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function monthly()
    {
        try {
            $start_month = date('Y-m-01');
            $end_month = date('Y-m-t');
            $monthly = DB::table('grand_total')
                    ->selectRaw('categories.name,MONTH(rdate) AS month,SUM(assets) as assets,SUM(liabilities) as liabilities')
                    ->join('categories', 'categories.id', '=', 'grand_total.category_id')
                    ->where('rdate','>=',$start_month)->where('rdate','<=',$end_month)
                    ->groupBy('category_id')
                    ->get();
            $data=[];
            foreach($monthly as $mon){
                $data[$mon->name] = [
                    'assets' => $mon->assets,
                    'liabilities' => $mon->liabilities
                ];
            }
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function threeMonth()
    {
        try {
            $start_month = date("Y-m-01",strtotime("-2 Months"));
            $end_month = date('Y-m-t');
            $monthly = DB::table('grand_total')
                    ->selectRaw('categories.name,MONTH(rdate) AS month,SUM(assets) as assets,SUM(liabilities) as liabilities')
                    ->join('categories', 'categories.id', '=', 'grand_total.category_id')
                    ->where('rdate','>=',$start_month)->where('rdate','<=',$end_month)
                    ->groupBy('category_id')
                    ->get();
            $data=[];
            foreach($monthly as $mon){
                $data[$mon->name] = [
                    'assets' => $mon->assets,
                    'liabilities' => $mon->liabilities
                ];
            }
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function yearly()
    {
        try {
            $start_month = date("Y-01-01");
            $end_month = date('Y-m-t');
            $monthly = DB::table('grand_total')
                    ->selectRaw('categories.name,MONTH(rdate) AS month,SUM(assets) as assets,SUM(liabilities) as liabilities')
                    ->join('categories', 'categories.id', '=', 'grand_total.category_id')
                    ->where('rdate','>=',$start_month)->where('rdate','<=',$end_month)
                    ->groupBy('category_id')
                    ->get();
            $data=[];
            foreach($monthly as $mon){
                $data[$mon->name] = [
                    'assets' => $mon->assets,
                    'liabilities' => $mon->liabilities
                ];
            }
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function userAdd(Request $req)
    {
        try{
            $user = User::create([
                'name' => $req->name,
                // 'email' => $req->email,
                'password' => Hash::make($req->password),
                // 'unit_name' => $req->unit_name,
                // 'question' => $req->question,
                'role' => $req->role,
            ]);
            $user->assignRole($req->role);
            $this->syncUserPermissionsFromRole($user);
        }catch(\Exception $e){
            session()->flash('error', 'User '.$req->name.' Already Exists!!!');
            return redirect()->route('userslist');
        }

        if($user){
            session()->flash('success', 'User Added Successfully!!!');
            return redirect()->route('userslist');
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect()->route('userslist');
        }
    }

    public function usersList()
    {
        $id = Auth::user()->id;
        $userslist = User::where(function($query){
            $query->where('role','!=',1)
                  ->orWhere('role',null);
        })->where('id','!=',$id)->paginate(10);
        $roles = Role::get();
        $title = 'Users';
        return view('users.userslist',compact('userslist','title','roles'));
    }

    public function userEdit($id)
    {
        $user = User::find($id);
        $userrole = count($user->roles)>0?$user->roles[0]->name:'';
        $title = 'Edit User';
        $roles = Role::get();
        return view('users.useredit',compact('user','title','roles','userrole'));
    }

    public function userUpdate(Request $req)
    {
        if($req->role!=''){
            DB::table('model_has_roles')->where('model_id',$req->id)->delete();
            DB::table('model_has_permissions')->where('model_id',$req->id)->delete();
            $user = User::find($req->id);
            $user->assignRole($req->role);
            $this->syncUserPermissionsFromRole($user);
        }else{
            DB::table('model_has_roles')->where('model_id',$req->id)->delete();
        }
        $user = User::whereId($req->id)->update([
            "name"=>$req->name,
            // "email"=>$req->email,
            // "unit_name"=>$req->unit_name,
            // "question"=>$req->question,
            "role"=>$req->role,
        ]);
        
        if($user){
            session()->flash('success', 'User Inforamtion Updated Successfully!!!');
            return redirect()->route('userslist');
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect()->route('userslist');
        }
    }

    public function userpassword(Request $req)
    {
        $user = User::whereId($req->id)->update([
            "password"=>Hash::make($req->password),
        ]);
        if($user){
            session()->flash('success', 'User Password Changed Successfully!!!');
            return redirect()->route('userslist');
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect()->route('userslist');
        }
    }

    public function userDelete($id)
    {
        $user = User::find($id)->delete();
        if($user){
            session()->flash('success', 'User Deleted Successfully!!!');
            return redirect()->route('userslist');
        }else{
            session()->flash('error', 'User Does Not Exist With Given ID!!!');
            return redirect()->route('userslist');
        }
    }

    public function userProfile()
    {
        $title = "Profile";
        return view('users.userprofile',compact('title'));
    }

    public function userImageUpload(Request $req)
    {
        if($user = User::find($req->userid)){
            if($req->hasFile('profilepic')) {
                $file = $req->file('profilepic');
                $filePath = $file->store('profilepic', 'public');
            }else{
                $filePath='';
            }
            $user->profile_pic = $filePath;
            if($user->save()){
                session()->flash('success', 'User Image Uploaded Successfully!!!');
                return redirect()->route('userslist');
            }else{
                session()->flash('error', 'Image Not Uploaded!!!');
                return redirect()->route('userslist');
            }
        }else{
            session()->flash('error', 'User Not Found!!!');
            return redirect()->route('userslist');
        }
    }

    public function profilepassword(Request $req)
    {
        $user = User::whereId($req->id)->update([
            "password"=>Hash::make($req->password),
        ]);
        if($user){
            session()->flash('success', 'Profile Password Changed Successfully!!!');
            Auth::logout();
            return redirect('/');
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect()->back();
        }
    }

    private function syncUserPermissionsFromRole(User $user): void
    {
        $role = $user->roles()->first();
        if (! $role) {
            return;
        }

        $permissionNames = $role->permissions->pluck('name')->filter()->values()->all();
        if ($permissionNames === []) {
            return;
        }

        $user->syncPermissions($permissionNames);
    }

    public function getPublicFundSummary()
    {
        try {
            // Get Public Fund category ID
            $publicFundId = 11; // Based on our database check
            
            // Get all subcategories of Public Fund
            $subcategories = DB::table('categories')
                ->where('parent_id', $publicFundId)
                ->get();
            
            $chartData = [];
            
            foreach ($subcategories as $subcategory) {
                $subcategoryKey = strtolower(str_replace(" ", "_", $subcategory->name));
                
                // Get total allotment for this subcategory
                $totalAllotment = DB::table('total_allotments')
                    ->where('category_id', $publicFundId)
                    ->where('subcategory_id', $subcategory->id)
                    ->whereYear('allotment_date', date('Y'))
                    ->sum('allotment_amount');
                
                // Get PCDA booking (amount actually booked by PCDA, not sent to PCDA)
                $pcdaBooking = DB::table('pcda_transactions')
                    ->where('category_id', $publicFundId)
                    ->where('subcategory_id', $subcategory->id)
                    ->where('transaction_type', 'booked_by_pcda')
                    ->whereYear('transaction_date', date('Y'))
                    ->sum('amount');
                
                // Get total expenditure from vouchers for this subcategory
                // Vouchers store subcategory amounts in voc_json field
                $vouchers = DB::table('vouchers')
                    ->where('category_id', $publicFundId)
                    ->where('voc_type', 'Payment')
                    ->whereYear('voc_date', date('Y'))
                    ->select('voc_json')
                    ->get();
                
                $totalExpenditure = 0;
                foreach ($vouchers as $voucher) {
                    $voucherData = $voucher->voc_json;
                    if ($voucherData && isset($voucherData[$subcategoryKey])) {
                        $totalExpenditure += (float) $voucherData[$subcategoryKey];
                    }
                }
                
                $chartData[] = [
                    'name' => $subcategory->name,
                    'total_allotment' => (float) $totalAllotment,
                    'pcda_booking' => (float) $pcdaBooking,
                    'total_expenditure' => (float) $totalExpenditure
                ];
            }
            
            return $chartData;
        } catch (\Exception $e) {
            return [];
        }
    }
}
