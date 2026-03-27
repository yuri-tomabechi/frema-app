<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\Models\Purchase;
use App\Models\Message;

class MessageController extends Controller
{
    public function show($purchase_id)
    {
        $purchase = Purchase::with([
            'item.user',
            'messages.user',
        ])->findOrFail($purchase_id);

        $user = auth()->user();

        if (
            $purchase->user_id !== $user->id &&
            $purchase->item->user_id !== $user->id
        ) {
            abort(403);
        }

        $purchases = Purchase::with('item')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('item', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->where('status', 'trading')
            ->get();

        $messages = $purchase->messages()->with('user')->oldest()->get();

        return view('message.show', compact('purchase', 'purchases', 'messages'));
    }


    public function store(MessageRequest $request, $purchase_id)
    {
        $purchase = Purchase::with('item')->findOrFail($purchase_id);

        $user = auth()->user();

        if (
            $purchase->user_id !== $user->id &&
            $purchase->item->user_id !== $user->id
        ) {
            abort(403);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('message_images', 'public');
        }

        Message::create([
            'purchase_id' => $purchase->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'image' => $imagePath,
        ]);

        return redirect()->route('message.show', $purchase->id);
    }
}
