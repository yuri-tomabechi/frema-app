<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $request->validate([
            'comment' => 'required|max:255'
        ]);

        Comment::create([
            'item_id' => $item_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        return back();
    }
}
