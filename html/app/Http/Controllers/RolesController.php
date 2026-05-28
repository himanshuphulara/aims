<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class RolesController extends Controller
{
    public function roles()
    {
        $rolespermissions = Role::with('permissions')->get();
        $roles = Role::paginate(10);
        $title = "Roles";
        return view('roles',compact('roles','title','rolespermissions'));
    }

    public function roleStore(Request $req)
    {
        try{
            $role = Role::create(['name' => $req->role_name]);
        }catch(\Exception $e){
            session()->flash('error', 'Role '.$req->role_name.' Already Exists!!!');
            return redirect()->route('roles');
        }
        if($role){
            session()->flash('success', 'Role Added Successfully!!!');
            return redirect()->route('roles');
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect()->route('roles');
        }
    }

    public function roleUpdate(Request $req)
    {
        $role = Role::whereId($req->role_id)->update([
            "name"=>$req->name,
        ]);
        if($role){
            session()->flash('success', 'Role Updated successfully!!!');
            return redirect()->route('roles');
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect()->route('roles');
        }
    }

    public function roleDelete($id)
    {
        $role = Role::find($id)->delete();
        if($role){
            session()->flash('error', 'Role Deleted Successfully!!!');
            return redirect()->route('roles');
        }else{
            session()->flash('error', 'Role Does Not Exist With Given ID!!!');
            return redirect()->route('roles');
        }
    }

    public function roleGetPermissions($roleid)
    {
        // $data = Permission::pluck('type','name')->toArray();
        // print_r($data);die;
        // $routes = Route::getRoutes();
        // $routeNames = [];

        // foreach ($routes as $route) {
        //     if ($route->getName()) {
        //         $routeNames[] = $route->getName();
        //     }
        // }
        // $valuesToRemove = ['signup', 'signin','logout'];

        // $result = array_diff($routeNames, $valuesToRemove);

        // // Reindex the array to remove gaps
        // // $routeNames = array_values($result);
        // $routeNames = $data;

        // return $routeNames;
        $role = Role::find($roleid);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        // $permissions = Permission::all();
        // $permissions = $routeNames;
        $permissions = Permission::pluck('type','name')->toArray();
        $title = "Role Permissions";
        $roleid = $roleid;
        return view('rolespermissions',compact('permissions','title','roleid','rolePermissions'));
    }
    public function roleSetPermissions(Request $req)
    {
        $role = Role::find($req->roleid);

        if (!$role) {
            session()->flash('error', 'Role Not Found!!!');
            return redirect()->route('roles');
        }

        $users = User::role($role->name)->get();
        foreach($users as $user){
            DB::table('model_has_permissions')->where('model_id',$user->id)->delete();
        }
        
        $permissions = $req->input('permissions', []);
        array_push($permissions,'dashboard');
        
        $existingPermissions = $role->permissions->pluck('name')->toArray();

        $permissionsToAdd = array_diff($permissions, $existingPermissions);
        $permissionsToRemove = array_diff($existingPermissions, $permissions);

        if ($permissionsToAdd) {
            $role->givePermissionTo($permissionsToAdd);
        }

        if ($permissionsToRemove) {
            $role->revokePermissionTo($permissionsToRemove);
        }
        
        foreach($users as $user){
            $user = User::find($user->id);
            $role =  $user->roles->first();
            $per = $role->permissions;
            $existingPermissions = $role->permissions->pluck('name')->toArray();
            $user->givePermissionTo($existingPermissions);
        }
        if($role){
            session()->flash('success', 'Permissions Are Set Successfully!!!');
            return redirect()->route('roles');
        }else{
            session()->flash('error', 'Something Went Wrong!!!');
            return redirect()->route('roles');
        }
    }
}
