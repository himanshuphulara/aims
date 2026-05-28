<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cheque;

class ChequeController extends Controller
{
    public function chequeList($cat_id,$start,$end)
    {
        $id = Auth::user()->id;
        $chequelist = Cheque::where('category_id',$cat_id)->whereBetween('cheque_date',[$start,$end])->get();
        $title = 'Cheque';
        return view('cheque.chequelist',compact('chequelist','title'));
    }

    public function chequeAdd(Request $req)
    {
        if ($req->hasFile('cheque_file')) {
            $file = $req->file('cheque_file');
            $cheque_file = $file->store('cheques', 'public');
        }else{
            $cheque_file='';
        }
        $cheque = new Cheque();
        $cheque->category_id = $req->category_id;
        $cheque->user_id = Auth::id();
        $cheque->cheque_detail = $req->cheque_detail;
        $cheque->cheque_number = $req->cheque_number;
        $cheque->cheque_date = $req->cheque_date;
        $cheque->cheque_amount = $req->cheque_amount;
        $cheque->cheque_file = $cheque_file;
        $cheque->cheque_status = $req->cheque_status;
        if ($cheque->save()){
            session()->flash('success', 'Cheque Details Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }
    }

    public function chequeEdit(Request $req)
    {
        $cheque = Cheque::find($req->cheque_id);
        if($cheque){
            if ($req->hasFile('cheque_file')) {
                $file = $req->file('cheque_file');
                $cheque_file = $file->store('cheques', 'public');
            }else{
                $cheque_file=$req->cheque_file_path;
            }
            $cheque->category_id = $req->category_id;
            $cheque->user_id = Auth::id();
            $cheque->cheque_detail = $req->cheque_detail;
            $cheque->cheque_number = $req->cheque_number;
            $cheque->cheque_date = $req->cheque_date;
            $cheque->cheque_amount = $req->cheque_amount;
            $cheque->cheque_file = $cheque_file;
            $cheque->cheque_status = $req->cheque_status;
            if ($cheque->save()){
                session()->flash('success', 'Cheque Details Updated Successfully!!!');
                return redirect()->back();
            }else{
                session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
                return redirect()->back();
            }
        }else{
            session()->flash('error', 'Cheque ID Not Found!!!');
            return redirect()->back();
        }
    }

    public function chequeDelete($id)
    {
        $cheque = Cheque::find($id);
        if($cheque->delete()){
            session()->flash('success', 'Cheque Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Cheque Not Found!!!');
            return redirect()->back();
        }
    }

    public function chequeClear($id)
    {
        $cheque = Cheque::find($id);
        if($cheque){
            $cheque->cheque_status = 1; // Mark as cleared
            if ($cheque->save()){
                session()->flash('success', 'Cheque Cleared Successfully!!!');
                return redirect()->back();
            }else{
                session()->flash('error', 'Something Went Wrong!!!');
                return redirect()->back();
            }
        }else{
            session()->flash('error', 'Cheque Not Found!!!');
            return redirect()->back();
        }
    }
}
