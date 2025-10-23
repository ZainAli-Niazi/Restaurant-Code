<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');  
    }

    public function authenticate(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.login')
                ->withInput()
                ->withErrors($validator);
        }

         
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->route('pos.index')
                ->with('success', 'You have logged in successfully.    ');  
        }else {
            return redirect()->route('admin.login')
               
                ->with('error', 'Username or password is incorrect.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}
