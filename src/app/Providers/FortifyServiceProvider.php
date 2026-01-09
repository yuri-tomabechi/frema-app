<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
// use Illuminate\Support\Facades\Validator;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        RateLimiter::for('login', function ($job) {
            return Limit::perMinute(50);   // ← 1分に50回までOK
        });


        // 会員登録画面
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログイン画面
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 独自の登録処理クラスを指定
        Fortify::createUsersUsing(CreateNewUser::class);

        // 登録成功後のリダイレクト先
        Fortify::verifyEmailView(function () {
            return view('auth.verify-notice');
        });

}
}