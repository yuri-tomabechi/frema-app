@extends('layouts.profile')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="detail__flex">
    <div class="item__img">
        <img src="{{asset('storage/' . $item->item_url) }}" alt="{{ $item->name }}">
    </div>
    <div class="detail__box">
        <h2 class="item__name">{{ $item->name }}</h2>
        <p class="bland__name">{{ $item->brand_name ?? 'ブランドなし'}}</p>
        <p class="price">¥{{ number_format($item->price) }}</p>
        <div class="button">
            <div class="favorite">
                <form action="{{ route('item.like', $item->id) }}" method="POST">
                    @csrf
                    <button class="favorite-btn" style="background:none;border:none;">
                        {{-- <img src="{{ asset('images/favorite.svg_transparent.png') }}" class="favorite-icon" alt=""> --}}
                        <svg class="favorite {{ $item->likes()->where('user_id', auth()->id())->exists() ? 'active' : '' }}" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01z"
                                fill="#FFFFFF"
                                stroke="#000000"
                                stroke-width="0.7"/>
                        </svg>
                    </button>
                </form>
                <p>{{ $item->likes->count() }}</p>
            </div>
            <div class="comment">
                <img src="{{ asset('images/comment.png')}}" alt="">
                <p>{{ $item->comments_count }}</p>
            </div>
        </div>
        @if (! $item->is_sold)
            <div class="btn">
                <a href="{{ route('purchase.show', $item->id) }}">購入手続きへ</a>
            </div>
        @endif
        <div class="detail">
            <h3>商品説明</h3>
            <p>{{ $item->description }}</p>
            
        </div>
        <div class="information">
            <h3>商品の情報</h3>
            <div class="category__flex">
                <p class="category__h1">カテゴリー</p>
                <div class="category">
                    @foreach($item->categories as $category)
                    <p class="category__name">{{ $category->name }}</p>
                    @endforeach
                </div>
            </div>
            <div class="condition__flex">
                <p class="condition__h1">商品の状態</p>
                <p class="condition">{{ $item->condition }}</p>
            </div>
        </div>
        <div class="comment__box">
            <h3>コメント<span>({{ $item->comments->count() }})</span></h3>
            @foreach($item->comments as $comment)
            <div class="comment__user">
                <img src="{{ asset('images/default_gray.png')}}" alt="" class="user__icon">
                <div class="user__name">{{ $comment->user->name }}</div>
            </div>
            <div class="comment__area">
                <p>{{ $comment->comment }}</p>
            </div>
            @endforeach
            <h4>商品へのコメント</h4>
            <form action="{{ route('item.comment', $item->id) }}" method="POST">
                @csrf
                <textarea name="comment" id="" rows="5"></textarea>
                <div class="form__error">
                    @error('comment')
                    {{ $message }}
                    @enderror
                </div>
                <div class="btn">
                    <button type="submit" class="comment__btn">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelector('.favorite-btn').addEventListener('click', function () {
        this.querySelector('svg').classList.toggle('active');
    });
    </script>
</div>


@endsection