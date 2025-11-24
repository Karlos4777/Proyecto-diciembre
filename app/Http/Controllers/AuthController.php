<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $credenciales=$request->only('email', 'password');
        $remember = $request->has('remember');

        if(Auth::attempt($credenciales, $remember)){
            $user= Auth::user();

            if($user->activo){
                $request->session()->regenerate();
                return redirect()->intended();
            }else{
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Su cuenta esta inactiva. Contacte con el administrador');
            }
        }
        return back()->with('error', 'Las credenciales no son correctas')->withInput($request->only('email', 'remember'));
    }
}
