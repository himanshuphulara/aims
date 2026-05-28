<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ASTBController extends Controller
{
    public function getASTB($item_id)
    {
        $astb = Items::find($item_id);
        $title = "Edit Property";
        // print_r($astb);
        //$subcategories = Category::where('parent_id',$voucher->category_id)->get();
        return view('astb.astbitems',compact('title','astb','item_id'));
    }

    public function astbUpdate(Request $req)
    {
        // if ($req->hasFile('civ_upload')) {
        //     $file = $req->file('civ_upload');
        //     $filePath = $file->store('civs', 'public');
        // }else{
        //     $filePath=$req->filePath;
        // }
        // $json_data = json_decode($req->jsonData);
        $start = $req->start;
        $end = $req->end;
        //deprecation amount (qty*rate=amt) amt with dep %
        $dep_amt = ($req->amt * $req->dep_per)/100;
        $dep_amt = round($dep_amt,2);

        //amount after deprecation amt-dep_amt
        $amt_after_depr = $req->amt - $dep_amt;
        $amt_after_depr = round($amt_after_depr,2);

        //unserviceable amount rate * repairable,auction,destroyable qty if any
        if($req->repairable!='' && $req->repairable!=0){
            $unsv_items_amt = $req->rate * $req->repairable;
        }else if($req->auction!='' && $req->auction!=0){
            $unsv_items_amt = $req->rate * $req->auction;
        }else if($req->destroyable!='' && $req->destroyable!=0){
            $unsv_items_amt = $req->rate * $req->destroyable;
        }else{
            $unsv_items_amt = 0;
        }
        $unsv_items_amt = round($unsv_items_amt,2);

        //present value after depr amt - unserv item amt
        $pre_value = $amt_after_depr - $unsv_items_amt;
        $pre_value = round($pre_value,2);

        // die; 
        $items = Items::find($req->id);
        $items->category_id = $req->cat_id;
        $items->user_id = Auth::id();
        $items->parent_item_id = $req->parent_item_id;
        $items->property_type = $req->property_type;
        $items->lpno = $req->lpno;
        $items->items = $req->items;
        $items->au = $req->au;
        $items->date = $req->date;
        $items->qty = $req->qty;
        $items->rate = $req->rate;
        $items->amt = $req->amt;
        $items->serviceable = $req->serviceable??0;
        $items->repairable = $req->repairable??0;
        $items->auction = $req->auction??0;
        $items->destroyable = $req->destroyable??0;
        $items->dep_per = $req->dep_per??0;
        $items->dep_amt = $dep_amt??'0.00'; 
        $items->amt_after_depr = $amt_after_depr??'0.00'; 
        $items->unsv_items_amt = $unsv_items_amt??'0.00'; 
        $items->pre_value = $pre_value??'0.00'; 
        $items->rboo = $req->rboo; 
        $items->astb_done = 1; 
        // $civ->civ_upload = $filePath; 
        $route = "fundvouchers/".$req->cat_id."?start_date=".$start."&end_date=".$end;
        if($items->save()){
            session()->flash('success', 'ASTB Details Updated Successfully!!!');
            return redirect($route);
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect($route);
        } 
    }
}
