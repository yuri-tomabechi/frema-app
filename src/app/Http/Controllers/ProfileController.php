<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Purchase;
use App\Http\Requests\ProfileRequest;


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

        $averageRating = null;

        $unreadTotal = Message::whereHas('purchase', function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('item', function ($q2) use ($user) {
                        $q2->where('user_id', $user->id);
                    });
            })
                ->where(function ($q) use ($user) {
                    $q->where('status', 'trading')
                        ->orWhere(function ($q2) use ($user) {
                            $q2->where('status', 'paid')
                                ->whereDoesntHave('reviews', function ($reviewQuery) use ($user) {
                                    $reviewQuery->where('reviewer_id', $user->id);
                                });
                        });
                });
        })
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();

        if ($user->reviewsReceived()->exists()) {
            $averageRating = round($user->reviewsReceived()->avg('rating'));
        }

        // 購入一覧
        if ($page === 'buy') {

            $purchases = Purchase::where('user_id', auth()->id())
                ->where('status', 'paid')
                ->get();

            return view('profile.buy', compact('user', 'purchases', 'averageRating', 'unreadTotal'));

        }
        if ($page === 'trade') {
            $purchases = Purchase::with(['item', 'reviews'])
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhereHas('item', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                })
                ->where(function ($query) use ($user) {
                    $query->where('status', 'trading')
                        ->orWhere(function ($q) use ($user) {
                            $q->where('status', 'paid')
                                ->whereDoesntHave('reviews', function ($reviewQuery) use ($user) {
                                    $reviewQuery->where('reviewer_id', $user->id);
                                });
                        });
                })
                ->withCount([
                    'messages as unread_count' => function ($query) use ($user) {
                        $query->where('user_id', '!=', $user->id)
                            ->where('is_read', false);
                    }
                ])
                ->withMax('messages', 'created_at')
                ->orderByDesc('messages_max_created_at')
                ->get();

            return view('profile.trade', compact('user', 'purchases', 'averageRating', 'unreadTotal'));
        }
        // 出品一覧
        {
            $items = $user->items;  // ★ 自分の出品商品を取得
            return view('profile.sell', compact('user', 'items', 'averageRating', 'unreadTotal'));
        }

    }

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
            $path = $request->file('icon_url')->store('profile_images', 'public');
            $user->icon_url = $path;
        }
        $user->save();

        return redirect('/mypage');
 

    }
}