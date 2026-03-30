<?php

namespace App\Http\Controllers;


class SellController extends Controller
{
    public function showSellForm()
    {
        $categories = \App\Models\Category::all();
        return view('sell', compact('categories'));
    }
}
