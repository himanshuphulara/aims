<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function signUp(Request $req)
    {
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);

        if($user){
            return redirect('/signup')->with('success', 'User Added Successfully!!!.');
        }
    }

    public function signIn(Request $req)
    {
        $validator =  $req->validate([
            // 'email' => 'required',
            'name' => 'required',
            'password' => 'required',
            // 'unit_name' => 'required',
            // 'question' => 'required',
        ]);
        $validate = validator($validator); 
        if($validate->fails()){
            return back()->withErrors($validate)->withInput();
        }
        // $credentials = $req->only('email', 'password','unit_name','question');
        $credentials = $req->only('name', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('/dashboard');
        }
        return redirect('/')->with('mismatch', 'Wrong Credentials.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
