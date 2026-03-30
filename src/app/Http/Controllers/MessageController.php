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
            'user',
            'item.user',
            'messages.user',
            'reviews',
        ])->findOrFail($purchase_id);

        $user = auth()->user();

        $purchase->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if (
            $purchase->user_id !== $user->id &&
            $purchase->item->user_id !== $user->id
        ) {
            abort(403);
        }

        $hasReviewed = $purchase->reviews->contains(function ($review) use ($user) {
            return $review->reviewer_id === $user->id;
        });

        $isBuyer = $purchase->user_id === $user->id;
        $isSeller = $purchase->item->user_id === $user->id;

        if ($isBuyer) {
            $purchases = Purchase::with(['item', 'reviews'])
                ->where('user_id', $user->id)
                ->where('status', 'trading')
                ->where('id', '!=', $purchase->id)
                ->get();
        } else {
            $purchases = Purchase::with(['item', 'reviews'])
                ->whereHas('item', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
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
                ->where('id', '!=', $purchase->id)
                ->get();
        }

        $messages = $purchase->messages()->with('user')->oldest()->get();

        return view('message.show', compact(
            'purchase',
            'purchases',
            'messages',
            'hasReviewed'
        ));
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

    public function update(MessageRequest $request, Message $message)
    {
        // 自分のメッセージしか編集できない
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $message->update([
            'message' => $request->message,
        ]);

        return redirect()->route('message.show', $message->purchase_id);
    }

    public function destroy(Message $message)
    {
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $purchaseId = $message->purchase_id;

        $message->delete();

        return redirect()->route('message.show', $purchaseId);
    }
}
