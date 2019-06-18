<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function PassChange()
    {
        return view('auth.password_change');
    }

    public function PassUpdate(Request $request)
    {
        $password = Auth::user()->password;
        $oldpass=$request->oldpassword;
        
        if (Hash::check($oldpass, $password)) {
            
            $user=User::find(Auth::id());
            $user->password=Hash::make($request->password);
            $user->save();
            Auth::logout();

            if ($user->save()) {
            $notification=array(
                'message'=>'Password Change Successfully',
                'alert-type'=>'success'
            );

                return Redirect()->route('login')->with($notification);
            }else{
                $notification=array(
                    'message'=>"Password Not Matched!",
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }

        } else{
            $notification=array(
                    'message'=>"Password Not Matched!",
                    'alert-type'=>'error'
                );
                return Redirect()->back()->with($notification);
        }
    }
}
