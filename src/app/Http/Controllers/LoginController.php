<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        if(!auth()->attempt($request->only('email', 'password'))){
            return back()->withErrors([
                'email' => 'ログイン情報が登録されいません',
            ])->onlyInput('email');
        }
        return redirect()->intended('/dashboard');
    }
}
