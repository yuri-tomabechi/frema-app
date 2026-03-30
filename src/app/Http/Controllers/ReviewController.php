<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Models\Purchase;
use App\Models\Review;
use App\Mail\TradeCompletedMail;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, $purchase_id)
    {
        $purchase = Purchase::with(['item.user', 'user'])->findOrFail($purchase_id);

        $authId = auth()->id();

        $isBuyer = $purchase->user_id === $authId;
        $isSeller = $purchase->item->user_id === $authId;

        if (!$isBuyer && !$isSeller) {
            abort(403);
        }

        if ($isBuyer) {
            $reviewedUserId = $purchase->item->user_id;
        } else {
            $reviewedUserId = $purchase->user_id;
        }

        Review::updateOrCreate(
            [
                'purchase_id' => $purchase->id,
                'reviewer_id' => $authId,
            ],
            [
                'reviewed_user_id' => $reviewedUserId,
                'rating' => $request->rating,
            ]
        );

        // 購入者が評価したタイミングで取引完了にする
        if ($isBuyer && $purchase->status !== 'paid') {
            $purchase->update([
                'status' => 'paid',
            ]);

            Mail::to($purchase->item->user->email)
                ->send(new TradeCompletedMail($purchase));
        }

        return redirect()->route('item.index');
    }
}
