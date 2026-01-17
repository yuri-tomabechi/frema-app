<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggle($item_id)
    {
        $user_id = auth()->id();

        $like = Like::where('item_id', $item_id)
            ->where('user_id', $user_id)
            ->first();

        if ($like) {
            // いいね解除
            $like->delete();
        } else {
            // いいね追加
            Like::create([
                'item_id' => $item_id,
                'user_id' => $user_id
            ]);
        }

        return back();
    }

    public function index()
    {
        $user_id = auth()->id();

        $items = Item::whereHas('likes', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->get();

        return view('item.like_list', compact('items'));
    }
}
