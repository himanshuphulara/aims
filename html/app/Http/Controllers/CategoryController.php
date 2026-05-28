<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Voucher;
use App\Models\VoucherBBF;

class CategoryController extends Controller
{
    /**
     * Add Category
     */
    public function categoryAdd(Request $req)
    {
        $exist = Category::where(['name'=>$req->name,'parent_id'=>0])->get();
        if(count($exist)>0){
            return redirect('categorylist')->with('error', 'Category '.$req->name.' already exists!!!');
        }
        
        $category = Category::create([
            'name' => $req->name,
            'has_total_allotment' => $req->has('has_total_allotment') ? 1 : 0
        ]);
        
        if($category){
            return redirect()->route('categorylist')->with('success', 'Category Added Successfully!!!');
        }else{
            return redirect()->route('categorylist')->with('error', 'Something Went Wrong!!!');
        }
    }

    /**
     * Category List
     */
    public function categoryList($pid=0)
    {
        $categorylist = Category::where('parent_id',$pid)->paginate(10);
        $title = 'Categories';
        return view('categories.category',compact('categorylist','title'));
    }

    /**
     * Add Sub Categories
     */
    public function subCategoryAdd(Request $req)
    {
        $route = 'subcategorylist/'.$req->sub_pat_id.'/'.$req->sub_cat_id.'';
        
        $exist = Category::where(['parent_id'=>$req->sub_cat_id,'name'=>$req->name])->get();
        if(count($exist)>0){
            session()->flash('error', 'Category '.$req->name.' already exists!!!');
            return redirect($route);
        }
        
        $category = Category::create([
            'name' => $req->name,
            'parent_id' => $req->sub_cat_id,
            'type' => $req->type,
            'subscription_amount' => $req->subscription_amount,
        ]);
        
        if($category){
            // Update existing vouchers to include the new subcategory in voc_json
            $newSubcategoryKey = strtolower(str_replace(" ", "_", $req->name));
            $vouchers = Voucher::where('category_id', $req->sub_cat_id)->get();
            
            foreach($vouchers as $voucher){
                if($voucher->voc_json){
                    $vocJson = $voucher->voc_json;
                    // Add new subcategory with value 0 if it doesn't exist
                    if(!isset($vocJson[$newSubcategoryKey])){
                        $vocJson[$newSubcategoryKey] = 0;
                        $voucher->voc_json = $vocJson;
                        $voucher->save();
                    }
                }
            }
            
            session()->flash('success', 'SubCategory Added Successfully! '.count($vouchers).' existing voucher(s) updated.');
            return redirect($route);
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect($route);
        }
    }
    
    /**
     * Sub Categories List
     */
    public function subcategoryList($pid,$id)
    {
        $isExist = Category::where('id',$id)->first();
        if(empty($isExist)){
            return redirect('categorylist');
        }
        $parent_cat = Category::where('id',$id)->get('name');
        $pat_name =  $parent_cat[0]->name;
        $subcategorylist = Category::where('parent_id',$id)->paginate(10);
        $title = $pat_name;
        return view('categories.subcategories',compact('subcategorylist','title','pid','id','pat_name'));
    }

    /**
     * Update Category
     */
    public function categoryUpdate(Request $req)
    {
        $category = Category::whereId($req->cat_id)->update([
            "name"=>$req->catname,
            "has_total_allotment" => $req->has('has_total_allotment') ? 1 : 0
        ]);

        if($category){
            return redirect()->route('categorylist')->with('success', 'Category Updated Successfully!!!');
        }else{
            return redirect()->route('categorylist')->with('error', 'Something Went Wrong!!!');
        }
    }

    /**
     * Update Sub Category
     */
    public function subcategoryUpdate(Request $req)
    {
        $route = 'subcategorylist/'.$req->sub_pat_id.'/'.$req->sub_cat_id.'';
        $voucheradded = Voucher::where('category_id',$req->sub_cat_id)->count();
        if($voucheradded>0){
            session()->flash('error', 'You cannot Update Sub Category because the categories are already linked to voucher data. If you wish to Update Category, please first back up your data, clean the existing data, and then try updating subcategory. Afterward, you can re-enter your data.');
            return redirect($route);
        }
        $category = Category::whereId($req->cat_id)->update([
            "name"=>$req->catname,
            "type"=>$req->cattype,
            'subscription_amount' => $req->catsubamt,
        ]);
        if($category){
            session()->flash('success', 'Sub Category Updated Successfully!!!');
            return redirect($route);
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect($route);
        }
    }

    /**
     * Delete Sub Category
     */
    public function subcategoryDelete($id,$catname,$pid,$mid)
    {
        $route = 'subcategorylist/'.$pid.'/'.$mid.'';
        
        // Check if there are nested subcategories
        $categories = Category::where('parent_id',$id)->get();
        if($categories->count()>0){
            session()->flash('error', 'Delete SubCategories Before Deleting '.$catname.' Category!!!');
            return redirect($route);
        }
        
        // Remove the subcategory key from all vouchers' voc_json
        $subcategoryKey = strtolower(str_replace(" ", "_", $catname));
        
        // Update vouchers table
        $vouchers = Voucher::where('category_id', $mid)->get();
        foreach($vouchers as $voucher){
            if($voucher->voc_json && isset($voucher->voc_json[$subcategoryKey])){
                $vocJson = $voucher->voc_json;
                unset($vocJson[$subcategoryKey]);
                $voucher->voc_json = $vocJson;
                $voucher->save();
            }
        }
        
        // Update voucher_bbf table
        $vouchersBBF = VoucherBBF::where('category_id', $mid)->get();
        foreach($vouchersBBF as $voucherBBF){
            if($voucherBBF->voc_json && isset($voucherBBF->voc_json[$subcategoryKey])){
                $vocJson = $voucherBBF->voc_json;
                unset($vocJson[$subcategoryKey]);
                $voucherBBF->voc_json = $vocJson;
                $voucherBBF->save();
            }
        }
        
        // Delete the category
        $user = Category::find($id)->delete();
        if($user){
            session()->flash('success', 'Category '.$catname.' Deleted Successfully! Updated '.count($vouchers).' voucher(s) and '.count($vouchersBBF).' BBF record(s).');
            return redirect($route);
        }else{
            session()->flash('error', 'Category '.$catname.' Not Deleted Successfully!!!');
            return redirect($route);
        }
    }

    /**
     * Delete Category
     */
    public function categoryDelete($id,$catname)
    {
        $categories = Category::where('parent_id',$id)->get();
        if($categories->count()>0){
            session()->flash('error', 'Delete SubCategories Before Deleting '.$catname.' Category!!!');
            return redirect()->route('categorylist');
        }else{
            $user = Category::find($id)->delete();
            if($user){
                session()->flash('success', 'Category '.$catname.' Deleted Successfullt!!!');
                return redirect()->route('categorylist');
            }else{
                session()->flash('error', 'Category '.$catname.' Not Deleted Successfullt!!!');
                return redirect()->route('categorylist');
            }
        }
    }
}
