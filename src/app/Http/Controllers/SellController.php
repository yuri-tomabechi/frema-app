<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class SellController extends Controller
{
    public function store(Request $request)
    {
        $path = $request->file('image')->store('images', 'public');
        $item = Item::create([
            'user_id' => auth()->id(),
            'item_url'=> $path,
            'name' => $request->name,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
        ]);
        $item->categories()->sync($request->categories);
        return redirect()->route('items.index');
        $item->user_id = Auth::id();
    }
    public function showSellForm()
    {
        $categories = \App\Models\Category::all();
        return view('sell', compact('categories'));
    }
}
