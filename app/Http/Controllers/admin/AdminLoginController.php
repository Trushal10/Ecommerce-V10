<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
//use Dotenv\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\validator;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function index() {
        return view('admin.login');
    }

    public function authenticate(Request $request) {

        $validator=validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if ($validator->passes()) {

            if (Auth::guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password],$request->get('remembar')) ){

                $admin = Auth::guard('admin')->user();
                if($admin -> role == 2) {
                    return redirect()->route('admin.dashboard');
                } else {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error','Email or Password is Incorrect');
                }

            } else {
                return redirect()->route('admin.login')->with('error','Email or Password is Incorrect');
            }

        }else {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

}

