<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function permissions()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $permissions = Permission::paginate(10);
        $title = "Permissions";
        return view('permissions',compact('permissions','title'));
    }

    public function store(Request $req)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        try{
            $permission = Permission::create(['name' => $req->role_name]);
        }catch(\Exception $e){
            session()->flash('error', 'Permission '.$req->role_name.' Already Exists!!!');
            return redirect()->route('permissions');
        }
        if($permission){
            session()->flash('success', 'Permission Added Successfully!!!');
            return redirect()->route('permissions');
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect()->route('permissions');
        }
    }

    public function update(Request $req)
    {
        $permission = Permission::whereId($req->role_id)->update([
            "name"=>$req->name,
        ]);
        if($permission){
            session()->flash('success', 'Permission Updated successfully!!!');
            return redirect()->route('permissions');
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect()->route('permissions');
        }
    }

    public function delete($id)
    {
        $permission = Permission::find($id)->delete();
        if($permission){
            session()->flash('error', 'Permission Deleted Successfully!!!');
            return redirect()->route('permissions');
        }else{
            session()->flash('error', 'Permission Does Not Exist With Given ID!!!');
            return redirect()->route('permissions');
        }
    }
}
