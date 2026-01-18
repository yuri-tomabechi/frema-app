<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class SellController extends Controller
{
    public function showSellForm()
    {
        $categories = \App\Models\Category::all();
        return view('sell', compact('categories'));
    }
}
