@extends('layouts.profile')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

<div class="my__page-profile">
    <div class="profile">
        <img src="{{ $user->icon_url ? asset('storage/' . $user->icon_url) : asset('images/default_gray.png') }}" alt="">
        <h2>{{ $user->name }}</h2>
    </div>
    <a href="/mypage/profile">プロフィールを編集</a>
</div>
<div class="list">
    <div class="list__flex">
        <a class="red" href="/mypage?page=sell">出品した商品</a>
        <a class="black center" href="/mypage?page=buy">購入した商品</a>
        <a class="black" href="/mypage?page=trade">取引中の商品</a>
    </div>
</div>
<section>
    <div class="item">
        <ul class="item__flex">
                @forelse($items as $item)
                <li>
                    <a href="{{ route('item.detail', $item->id) }}">
                        <img src="{{ asset('storage/' . $item->item_url) }}" alt="">
                        <p>{{ $item->name }}</p>
                    </a>
                </li>
                @empty
                    <p>出品した商品はありません。</p>
                @endforelse
        </ul>
    </div>
</section>

@endsection