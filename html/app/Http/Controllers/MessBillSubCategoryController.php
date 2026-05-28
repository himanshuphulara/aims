<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MessbillSubCategory;
use App\Models\Category;

class MessBillSubCategoryController extends Controller
{
    public function messBillCategory($cat_id,$start,$end)
    {
        $id = Auth::user()->id;
        $subcats = MessbillSubCategory::where([
            'category_id'=>$cat_id,
            'subcat_date'=>$start
            ])->get();
        $subcategories = Category::where('parent_id',$cat_id)->get(['id','name']);
        $title = 'Sub Categories';
        return view('messbill.messbillsubcategory',compact('subcats','title','subcategories'));
    }

    public function messbillAddSubcategory(Request $req)
    {
        foreach($req->label as $index => $label)
        {
            $subcat = MessbillSubCategory::create([
                'category_id' => $req->category_id,
                'user_id'     => Auth::id(),
                'main_category'  => $req->cat_name,
                'subcategory_name'  => $label,
                'amount'  => $req->amount[$index],
                'subcat_date'  => $req->subcat_date,
            ]);
        }
        if ($subcat){
            session()->flash('success', 'Sub Category Added Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
            return redirect()->back();
        }
    }

    public function messbillSubCategoryEdit(Request $req)
    {
        $subcat = MessbillSubCategory::find($req->id);
        if($subcat){
            $subcat->category_id = $req->category_id;
            $subcat->user_id = Auth::id();
            $subcat->main_category = $req->main_category;
            $subcat->subcategory_name = $req->subcategory_name;
            $subcat->amount = $req->amount;
            if ($subcat->save()){
                session()->flash('success', 'Sub Category Updated Successfully!!!');
                return redirect()->back();
            }else{
                session()->flash('error', 'Something Went Wrong Please Try Again Later!!!');
                return redirect()->back();
            }
        }else{
            session()->flash('error', 'Sub Category ID Not Found!!!');
            return redirect()->back();
        }
    }

    public function messbillSubCategoryDelete($id)
    {
        $subcat = MessbillSubCategory::find($id);
        if($subcat->delete()){
            session()->flash('success', 'Sub Category Deleted Successfully!!!');
            return redirect()->back();
        }else{
            session()->flash('error', 'Sub Category ID Not Found!!!');
            return redirect()->back();
        }
    }
}
