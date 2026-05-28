<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Properties;

class PropertiesController extends Controller
{
    public function propertiesList($cat_id)
    {
        $id = Auth::user()->id;
        $propertieslist = Properties::where('category_id',$cat_id)->get();
        $title = 'Properties';
        return view('properties.propertieslist',compact('propertieslist','title'));
    }

    public function propertiesAdd(Request $req)
    {
        if ($req->hasFile('pro_file')) {
            $file = $req->file('pro_file');
            $pro_file = $file->store('cheques', 'public');
        }else{
            $pro_file='';
        }
        $pro = new Properties();
        $pro->category_id = $req->category_id;
        $pro->user_id = Auth::id();
        $pro->pro_item = $req->pro_item;
        $pro->pro_date = $req->pro_date;
        $pro->pro_qty = $req->pro_qty;
        $pro->pro_total = $req->pro_total;
        $pro->pro_file = $pro_file;
        $pro->pro_remarks = $req->pro_remarks;
        if ($pro->save()){
            session()->flash('success', 'Property Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }
    }

    public function propertiesEdit(Request $req)
    {
        $pro = Properties::find($req->pro_id);
        if($pro){
            if ($req->hasFile('pro_file')) {
                $file = $req->file('pro_file');
                $pro_file = $file->store('pros', 'public');
            }else{
                $pro_file=$req->pro_file_path;
            }
            $pro->category_id = $req->category_id;
            $pro->user_id = Auth::id();
            $pro->pro_item = $req->pro_item;
            $pro->pro_date = $req->pro_date;
            $pro->pro_qty = $req->pro_qty;
            $pro->pro_total = $req->pro_total;
            $pro->pro_file = $pro_file;
            $pro->pro_remarks = $req->pro_remarks;
            if ($pro->save()){
                session()->flash('success', 'Propertiy Details Updated Successfully!!!');
                return redirect()->back();
            }else{
                session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
                return redirect()->back();
            }
        }else{
            session()->flash('error', 'Property ID Not Found!!!');
            return redirect()->back();
        }
    }

    public function propertiesDelete($id)
    {
        $pro = Properties::find($id);
        if($pro->delete()){
            session()->flash('success', 'Property Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Property Not Found!!!');
            return redirect()->back();
        }
    }
}
