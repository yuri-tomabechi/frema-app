<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // メール認証に進む場合（Fortify/Laravel標準メール確認使用）
        // イベント発火でメール送信
        $user->sendEmailVerificationNotification();

        // 認証メール送信完了画面へ遷移
        return redirect()->route('verification.notice');
    }
}
