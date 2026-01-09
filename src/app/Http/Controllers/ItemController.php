<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;


use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()
            ->when(auth()->check(), function ($query) {
                $query->where('user_id', '!=', auth()->id());
            })
            ->get();

        return view('item.index', compact('items'));

        $user = Auth::user();
        if ($user){
            if(!$user->hasVerifiedEmail()){
                return redirect()->route('verification.notice');
            }
            else if($user->hasVerifiedEmail() && $user->address == null){
                return redirect('/mypage/profile');
            }
        }
        $items = Item::with('categories')->get();
        return view('item.index', compact('items'));
    }


    public function show($id)
    {
        $item = Item::withCount(['likes', 'comments'])
            ->findOrFail($id);


        return view('item.detail', compact('item'));
    }

    public function store(ExhibitionRequest $request)
    {

        // ★ 画像を保存（public ディスク）
        $path = $request->file('item_url')->store('items', 'public');
        // 保存先 → storage/app/public/items/xxxx.jpg
        // $path = items/xxxx.jpg の形で返る
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
