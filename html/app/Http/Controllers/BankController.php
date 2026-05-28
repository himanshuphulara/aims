<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    public function stmtList($cat_id,$start,$end)
    {
        $id = Auth::user()->id;
        $bankstmt = BankStatement::where('category_id',$cat_id)->whereBetween('stmt_date',[$start,$end])->get();
        $title = 'Bank Statement';
        return view('bank.stmtlist',compact('title','bankstmt'));
    }

    public function stmtAdd(Request $req)
    {
        if ($req->hasFile('stmt_file')) {
            $file = $req->file('stmt_file');
            $stmt_file = $file->store('statements', 'public');
        }else{
            $stmt_file='';
        }
        $stmt = new BankStatement();
        $stmt->category_id = $req->category_id;
        $stmt->user_id = Auth::id();
        $stmt->stmt_label = $req->stmt_label;
        $stmt->stmt_amount = $req->stmt_amount;
        $stmt->stmt_date = $req->stmt_date;
        $stmt->stmt_file = $stmt_file;
        if ($stmt->save()){
            session()->flash('success', 'Statement Details Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }
    }

    public function stmtEdit(Request $req)
    {
        $stmt = BankStatement::find($req->stmt_id);
        if($stmt){
            if ($req->hasFile('stmt_file')) {
                $file = $req->file('stmt_file');
                $stmt_file = $file->store('statements', 'public');
            }else{
                $stmt_file=$req->stmt_file_path;
            }
            $stmt->category_id = $req->category_id;
            $stmt->user_id = Auth::id();
            $stmt->stmt_label = $req->stmt_label;
            $stmt->stmt_amount = $req->stmt_amount;
            $stmt->stmt_date = $req->stmt_date;
            $stmt->stmt_file = $stmt_file;
            if ($stmt->save()){
                session()->flash('success', 'Statement Details Updated Successfully!!!');
                return redirect()->back();
            }else{
                session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
                return redirect()->back();
            }
        }else{
            session()->flash('error', 'Statement ID Not Found!!!');
            return redirect()->back();
        }
    }

    public function stmtDelete($id)
    {
        $stmt = BankStatement::find($id);
        if($stmt->delete()){
            session()->flash('success', 'Statement Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Statement Not Found!!!');
            return redirect()->back();
        }
    }
}
