@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/message.css') }}">
@endsection

@section('content')
    <div class="trade-chat">
        <aside class="trade-sidebar">
            <h2 class="trade-sidebar__title">その他の取引</h2>

            <div class="trade-sidebar__list">
                @forelse($purchases as $tradePurchase)
                    <a href="{{ route('message.show', $tradePurchase->id) }}"
                        class="trade-sidebar__item {{ $purchase->id === $tradePurchase->id ? 'is-active' : '' }}">
                        {{ $tradePurchase->item->name }}
                    </a>
                @empty
                    <p class="trade-sidebar__empty">取引中の商品はありません</p>
                @endforelse
            </div>
        </aside>

        <section class="trade-main">
            <div class="trade-header">
                <div class="trade-header__user">
                    <div class="trade-header__icon">
                        @if (optional($purchase->item->user)->icon_url)
                            <img src="{{ asset('storage/' . $purchase->item->user->icon_url) }}" alt="">
                        @else
                            <div class="trade-header__icon-placeholder"></div>
                        @endif
                    </div>

                    <h1 class="trade-header__title">
                        「{{ $purchase->item->user->name ?? 'ユーザー名' }}」さんとの取引画面
                    </h1>
                </div>
            </div>

            <div class="trade-product">
                <div class="trade-product__image">
                    <img src="{{ asset('storage/' . $purchase->item->item_url) }}" alt="">
                </div>

                <div class="trade-product__info">
                    <h2 class="trade-product__name">{{ $purchase->item->name }}</h2>
                    <p class="trade-product__price">¥{{ number_format($purchase->item->price) }}</p>
                </div>
            </div>

            <div class="trade-messages">
                @forelse($messages as $message)
                    @php
                        $isMine = $message->user_id === auth()->id();
                    @endphp

                    <div class="trade-message {{ $isMine ? 'is-mine' : 'is-other' }}">
                        <div class="trade-message__meta">
                            @if (!$isMine)
                                <div class="trade-message__user">
                                    <div class="trade-message__icon">
                                        @if (optional($message->user)->icon_url)
                                            <img src="{{ asset('storage/' . $message->user->icon_url) }}" alt="">
                                        @else
                                            <div class="trade-message__icon-placeholder"></div>
                                        @endif
                                    </div>
                                    <span class="trade-message__name">{{ $message->user->name }}</span>
                                </div>
                            @else
                                <div class="trade-message__user trade-message__user--mine">
                                    <span class="trade-message__name">{{ $message->user->name }}</span>
                                    <div class="trade-message__icon">
                                        @if (optional($message->user)->icon_url)
                                            <img src="{{ asset('storage/' . $message->user->icon_url) }}" alt="">
                                        @else
                                            <div class="trade-message__icon-placeholder"></div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="trade-message__body-wrap {{ $isMine ? 'is-mine' : 'is-other' }}">
                            <div class="trade-message__body">
                                {{ $message->message }}
                            </div>
                        </div>

                        @if ($message->image)
                            <img src="{{ asset('storage/' . $message->image) }}" alt="">
                        @endif

                        @if ($isMine)
                            <div class="trade-message__actions">
                                <a href="#">編集</a>
                                <a href="#">削除</a>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="trade-messages__empty">まだメッセージはありません。</p>
                @endforelse
            </div>

            <form action="{{ route('message.store', $purchase->id) }}" method="POST" class="trade-form"
                enctype="multipart/form-data">
                @csrf

                <div class="trade-form__row">
                    <textarea name="message" class="trade-form__textarea" placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>

                    <label for="image" class="trade-form__image-label">画像を追加</label>
                    <input type="file" name="image" id="image" class="trade-form__image-input">

                    <button type="submit" class="trade-form__submit" aria-label="送信">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                            <path d="M3 20L21 12L3 4V10L15 12L3 14V20Z" stroke="currentColor" stroke-width="1.8"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                @error('message')
                    <p class="trade-form__error">{{ $message }}</p>
                @enderror

                @error('image')
                    <p class="trade-form__error">{{ $message }}</p>
                @enderror
            </form>
        </section>
    </div>
@endsection
