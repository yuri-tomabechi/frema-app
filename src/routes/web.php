<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


// Route::get('/email/verify', function () {
//     return view('auth.verify-notice');
// })->middleware('auth')->name('verification.notice');

// Route::post('/email/verification-notification',
//     function (Request $request) {
//         $request->user()->sendEmailVerificationNotification();

//         return back()->with('message', '認証メールを再送信しました。');
//     }
// )->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/', [ItemController::class, 'index'])->name('item.index');
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit'])->name('purchase.address.edit');
Route::put('/purchase/address/{item_id}', [PurchaseController::class, 'update'])
->name('purchase.address.update');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.detail');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/mypage', [ProfileController::class, 'show']);
    Route::post('/sell', [SellController::class, 'store'])->name('items.store');
    Route::get('/sell', [SellController::class, 'showSellForm'])->name('sell.form');
    // Route::get('/mypage', [ProfileController::class, 'showMyPage']);
    // Route::get('/mypage/profile', [ProfileController::class, 'showEditForm'])->middleware('auth', 'verified');
    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    // いいね
    Route::post('/item/{item_id}/like', [LikeController::class, 'toggle'])->name('item.like');
    Route::get('/mylist', [LikeController::class, 'index'])
        ->name('like.index')
        ->middleware('auth');
    // コメント追加
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('item.comment');
    // 決済
    Route::post('/purchase/{item}/checkout', [PurchaseController::class, 'checkout'])
    ->name('purchase.checkout');

    Route::get('/purchase/success', [PurchaseController::class, 'success'])
    ->name('purchase.success');
    
    Route::get('/purchase/cancel', [PurchaseController::class, 'cancel'])
    ->name('purchase.cancel');
    
    Route::post('/stripe/webhook', [PurchaseController::class, 'webhook']);
    
    Route::get('/purchase/complete', function () {
        return view('item.purchase_success');
    })->name('purchase.complete');
    
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
});




Route::get('/mylogout',
function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
}
);


// 検索
Route::get('/search', [ItemController::class, 'search'])->name('item.search');
