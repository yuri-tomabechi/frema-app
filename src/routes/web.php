<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/email/verify', function () {
    return view('auth.verify-notice');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification',
    function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', '認証メールを再送信しました。');
    }
)->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/mypage', [ProfileController::class, 'index']);
