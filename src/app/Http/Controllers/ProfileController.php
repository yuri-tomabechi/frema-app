<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Purchase;
use App\Http\Requests\ProfileRequest;

use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page');

        // 購入一覧
        if ($page === 'buy') {
            // $purchases = $user->purchases; // ★ 自分の購入商品を取得

            $purchases = Purchase::where('user_id', auth()->id())
                ->where('status', 'paid')
                ->get();

            return view('profile.buy', compact('user', 'purchases'));
        }else
        // 出品一覧
        {
            $items = $user->items;  // ★ 自分の出品商品を取得
            return view('profile.sell', compact('user', 'items'));
        }

    }

    // public function showMyPage()
    // {
    //     $user = auth()->user();
    //     return view('profile.sell' , compact('user'));
    // }
    public function showEditForm()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // 名前更新
        $user->name = $request->name;
        $user->post_code = $request->post_code;
        $user->address = $request->address;
        $user->building = $request->building;

        // 画像アップロード
        if ($request->hasFile('icon_url')) {
            // 保存（storage/app/public/profile_images）
            $path = $request->file('icon_url')->store('profile_images', 'public');
            $user->icon_url = $path;
        }
        $user->save();

        return redirect('/mypage');
 

    }
}