<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;

use App\Models\Purchase;
use App\Models\Item;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;



class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $purchase = Purchase::where('user_id', $user->id)
            ->where('item_id', $item_id)
            ->first();

        return view('item.purchase', compact('item', 'user', 'purchase'));
    }
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        return view('item.edit', compact('item', 'user'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $request->validate([
            'post_code' => 'required|string|regex:/^\d{3}-\d{4}$/|max:8',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        // 既存 purchase を取得（なければ作成）
        Purchase::updateOrCreate([
            'user_id' => $user->id,
            'item_id' => $item_id,
        ],
        [
            'status'    => 'pending',
            'payment'   => $request->payment,
            'post_code' => $request->post_code,
            'address'   => $request->address,
            'building'  => $request->building,
            ]
        );

        return redirect()->route('purchase.show', $item_id);
}
    public function store(PurchaseRequest $request, $item_id)
    {

        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $postCode = $request->post_code ?? '';
        $address  = $request->address ?? '';
        $building = $request->building ?? '';

        Purchase::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'item_id' => $item_id,
            ],
            [
                'status'    => 'trading',
                'payment'   => 'convenience',
                'post_code' => $postCode,
                'address'   => $address,
                'building'  => $building,
            ]
        );


        $item->update([
            'is_sold' => true,
        ]);

        return redirect()->route('purchase.complete');
    }


    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $request->validate(
            [
                'payment' => 'required',
            ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                    'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            'success_url' => route('purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchase.cancel'),
            'metadata'    => [
                'item_id' => $item->id,
            ],
        ]);

        return redirect($session->url);
        }

        public function success(Request $request)
        {
            Stripe::setApiKey(config('services.stripe.secret'));

            $sessionId = $request->get('session_id');

            $session = Session::retrieve($sessionId);

            $itemId = $session->metadata->item_id;

            $item = Item::find($itemId);

            if ($item) {
            $item->update(['is_sold' => true]);
        }

        Purchase::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'item_id' => $itemId,
            ],
            [
                'status'    => 'trading',
                'payment'   => 'card',
                'post_code' => $session->customer_details->address->postal_code ?? '',
                'address'   => $session->customer_details->address->line1 ?? '',
                'building'  => $session->customer_details->address->line2 ?? '',
            ]
        );


        return redirect()->route('item.index');
    }


    public function cancel()
    {
        return back()->with('error', '支払いがキャンセルされました');
    }


    public function webhook(Request $request)
    {
        return response('OK', 200);
    }

    public function complete($purchase_id)
    {
        $purchase = Purchase::findOrFail($purchase_id);

        $purchase->update([
            'status' => 'paid',
        ]);

        return redirect()->route('mypage', ['page' => 'buy']);
    }
}