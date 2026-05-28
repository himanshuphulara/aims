<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Officers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class OfficerDetailController extends Controller
{
    public function officerList($cat_id)
    {
        $id = Auth::user()->id;
        $officerlist = Officers::where('category_id',$cat_id)->get();
        $subscriptions = Category::where([
                'parent_id'=>$cat_id,
                'type'=>'Liabilities',
            ])->where('subscription_amount','!=','')
            ->get(['name','type','subscription_amount']);
        $title = 'Officers Record';
        return view('officers.officerslist',compact('officerlist','title','subscriptions'));
    }

    public function officerAdd(Request $req)
    {
        $subscriptions = $req->subscriptions ?? [];
        $officer = new Officers();
        $officer->category_id = $req->category_id;
        $officer->user_id = Auth::id();
        $officer->officer_rank = $req->officer_rank;
        $officer->officer_name = $req->officer_name;
        $officer->officer_tos = $req->officer_tos;
        $officer->officer_sos = $req->officer_sos;
        $officer->marital_status = $req->marital_status;
        $officer->subscriptions_json = $subscriptions;
        
        if ($officer->save()){
            session()->flash('success', 'Officer Detail Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }
    }

    public function officerEdit(Request $req)
    {
        $officer = Officers::find($req->officer_id);
        if($officer){
            $subscriptions = $req->subscriptions ?? [];
            $officer->category_id = $req->category_id;
            $officer->user_id = Auth::id();
            $officer->officer_rank = $req->officer_rank;
            $officer->officer_name = $req->officer_name;
            $officer->officer_tos = $req->officer_tos;
            $officer->officer_sos = $req->officer_sos;
            $officer->marital_status = $req->marital_status;
            $officer->subscriptions_json = $subscriptions;
            if ($officer->save()){
                session()->flash('success', 'Officer Detail Updated Successfully!!!');
                return redirect()->back();
            }else{
                session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
                return redirect()->back();
            }
        }else{
            session()->flash('error', 'Officer ID Not Found!!!');
            return redirect()->back();
        }
    }

    public function officerDelete($id)
    {
        $officer = Officers::find($id);
        if($officer->delete()){
            session()->flash('success', 'Officer Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Officer Not Found!!!');
            return redirect()->back();
        }
    }
}
