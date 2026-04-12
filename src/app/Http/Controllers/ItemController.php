<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;


use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $query = Item::latest();
        $user = Auth::user();
        if ($user) {
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            } else if ($user->hasVerifiedEmail() && $user->address == null) {
                return redirect('/mypage/profile');
            } else {
                $query->where('user_id', '!=', auth()->id());
            }
        }
        $items = $query->get();
        return view('item.index', compact('items'));
    }


    public function show($id)
    {
        $item = Item::with([
            'user.reviewsReceived.reviewer'
        ])
            ->withCount(['likes', 'comments'])
            ->findOrFail($id);

        $reviews = $item->user->reviewsReceived;

        $avgRating = $reviews->avg('rating');
        $avgRating = $avgRating ? round($avgRating) : null;

        return view('item.detail', compact('item', 'reviews', 'avgRating'));
    }

    public function store(ExhibitionRequest $request)
    {

        // ★ 画像を保存（public ディスク）
        $path = $request->file('item_url')->store('items', 'public');

        // ★ DB 保存
        $item = Item::create([
            'name' => $request->name,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'user_id' => auth()->id(),
            'item_url' => $path,
        ]);

        $item->categories()->attach($request->categories);
        return redirect('/mypage?page=sell');
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        session(['keyword' => $keyword]);

        $items = Item::when($keyword, function ($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        })->get();

        return view('item.index', compact('items', 'keyword'));
    }
}
