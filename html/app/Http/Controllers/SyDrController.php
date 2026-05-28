<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SyDr;

class SyDrController extends Controller
{
    public function sydrList($cat_id,$start,$end)
    {
        $id = Auth::user()->id;
        $sydrlist = SyDr::where('category_id',$cat_id)->whereBetween('sydr_date',[$start,$end])->get();
        $title = 'Sy Dr';
        return view('Sy.sydr',compact('sydrlist','title'));
    }

    public function sydrAdd(Request $req)
    {
        foreach($req->label as $index => $label)
        {
            $amount = $req->amount[$index];
            $sydr = SyDr::create([
                'category_id' => $req->category_id,
                'user_id'     => Auth::id(),
                'sydr_date'   => $req->sydr_date,
                'sydr_label'  => $label,
                'sydr_amount' => $amount,
            ]);
        }
        if ($sydr){
            session()->flash('success', 'SyDr Details Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }
    }

    public function sydrEdit(Request $req)
    {
        $sydr = SyDr::find($req->sydr_id);
        if($sydr){
            $sydr->category_id = $req->category_id;
            $sydr->user_id = Auth::id();
            $sydr->sydr_label = $req->label;
            $sydr->sydr_amount = $req->amount;
            $sydr->sydr_date = $req->sydr_date;
            if ($sydr->save()){
                session()->flash('success', 'SyDr Details Updated Successfully!!!');
                return redirect()->back();
            }else{
                session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
                return redirect()->back();
            }
        }else{
            session()->flash('error', 'SyDr ID Not Found!!!');
            return redirect()->back();
        }
    }

    public function sydrDelete($id)
    {
        $sydr = SyDr::find($id);
        if($sydr->delete()){
            session()->flash('success', 'SyDr Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'SyDr Not Found!!!');
            return redirect()->back();
        }
    }
}
