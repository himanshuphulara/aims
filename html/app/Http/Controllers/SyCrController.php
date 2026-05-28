<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SyCr;

class SyCrController extends Controller
{
    public function sycrList($cat_id,$start,$end)
    {
        $id = Auth::user()->id;
        $sycrlist = SyCr::where('category_id',$cat_id)->whereBetween('sycr_date',[$start,$end])->get();
        $title = 'Sy Cr';
        return view('Sy.sycr',compact('sycrlist','title'));
    }

    public function sycrAdd(Request $req)
    {
        foreach($req->label as $index => $label)
        {
            $amount = $req->amount[$index];
            $sycr = SyCr::create([
                'category_id' => $req->category_id,
                'user_id'     => Auth::id(),
                'sycr_date'   => $req->sycr_date,
                'sycr_label'  => $label,
                'sycr_amount' => $amount,
            ]);
        }
        if ($sycr){
            session()->flash('success', 'SyCr Details Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }
    }

    public function sycrEdit(Request $req)
    {
        $sycr = SyCr::find($req->sycr_id);
        if($sycr){
            $sycr->category_id = $req->category_id;
            $sycr->user_id = Auth::id();
            $sycr->sycr_label = $req->label;
            $sycr->sycr_amount = $req->amount;
            $sycr->sycr_date = $req->sycr_date;
            if ($sycr->save()){
                session()->flash('success', 'SyCr Details Updated Successfully!!!');
                return redirect()->back();
            }else{
                session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
                return redirect()->back();
            }
        }else{
            session()->flash('error', 'SyCr ID Not Found!!!');
            return redirect()->back();
        }
    }

    public function sycrDelete($id)
    {
        $sycr = SyCr::find($id);
        if($sycr->delete()){
            session()->flash('success', 'SyCr Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'SyCr Not Found!!!');
            return redirect()->back();
        }
    }
}
