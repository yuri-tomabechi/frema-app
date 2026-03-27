@extends('layouts.profile')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
    <div class="my__page-profile">
        <div class="profile">
            <img src="{{ $user->icon_url ? asset('storage/' . $user->icon_url) : asset('images/default_gray.png') }}"
                alt="">
            <h2>{{ $user->name }}</h2>
        </div>
        <a href="/mypage/profile">プロフィールを編集</a>
    </div>
    <div class="list">
        <div class="list__flex">
            <a class="black" href="/mypage?page=sell">出品した商品</a>
            <a class="black center" href="/mypage?page=buy">購入した商品</a>
            <a class="red" href="/mypage?page=trade">取引中の商品</a>
        </div>
    </div>
    <section>
        <div class="item">
            <ul class="item__flex">
                @forelse($purchases as $purchase)
                    <li>
                        <a href="{{ route('message.show', $purchase->id) }}">
                            <img src="{{ asset('storage/' . $purchase->item->item_url) }}" alt="">
                            <p>{{ $purchase->item->name }}</p>
                        </a>
                    </li>
                @empty
                    <p>取引中の商品はありません。</p>
                @endforelse
            </ul>
        </div>
    </section>
@endsection
