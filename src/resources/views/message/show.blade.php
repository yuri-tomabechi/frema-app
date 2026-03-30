@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/message.css') }}">
@endsection

@php
    $partner = $purchase->user_id === auth()->id() ? $purchase->item->user : $purchase->user;
@endphp

@php
    $isBuyer = $purchase->user_id === auth()->id();
    $isSeller = $purchase->item->user_id === auth()->id();

    $partner = $isBuyer ? $purchase->item->user : $purchase->user;
@endphp

@section('content')
    <div class="trade-chat">
        <aside class="trade-sidebar">
            <h2 class="trade-sidebar__title">その他の取引</h2>

            <div class="trade-sidebar__list">
                @forelse($purchases as $tradePurchase)
                    <a href="{{ route('message.show', $tradePurchase->id) }}" class="trade-sidebar__item">
                        {{ $tradePurchase->item->name }}
                    </a>
                @empty
                @endforelse
            </div>
        </aside>

        <section class="trade-main">
            <div class="trade-header">
                <div class="trade-header__user">
                    <div class="trade-header__icon">
                        @if (optional($partner)->icon_url)
                            <img src="{{ asset('storage/' . $partner->icon_url) }}" alt="">
                        @else
                            <div class="trade-header__icon-placeholder"></div>
                        @endif
                    </div>
                    <h1 class="trade-header__title">
                        「{{ $partner->name ?? 'ユーザー名' }}」さんとの取引画面
                    </h1>
                </div>
                @if ($purchase->user_id === auth()->id())
                    <div class="trade-header__complete">
                        <button type="button" class="complete__button" id="openReviewModal">
                            取引を完了する
                        </button>
                    </div>
                @endif
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

            <div class="trade-messages" id="tradeMessages">
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
                            <div class="trade-message__body-wrap {{ $isMine ? 'is-mine' : 'is-other' }}">
                                <div class="trade-message__image">
                                    <img src="{{ asset('storage/' . $message->image) }}" alt="">
                                </div>
                            </div>
                        @endif
                        @if ($isMine)
                            <div class="trade-message__actions">
                                <button type="button"
                                    onclick="editMessage({{ $message->id }}, '{{ addslashes($message->message) }}')">
                                    編集
                                </button>

                                <form action="{{ route('message.destroy', $message->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('削除しますか？')">削除</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="trade-messages__empty">まだメッセージはありません。</p>
                @endforelse
            </div>

            <form id="chatForm" action="{{ route('message.store', $purchase->id) }}" method="POST" class="trade-form"
                enctype="multipart/form-data">
                @csrf

                <div class="trade-form__row">
                    <textarea name="message" id="messageInput" class="trade-form__textarea" placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>

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
        <div id="editModal" class="edit-modal">
            <div class="edit-modal__content">
                <form id="editForm" method="POST" class="edit-flex">
                    @csrf
                    @method('PUT')

                    <textarea name="message" id="editMessageText"></textarea>
                    <div class="button-flex">
                        <button type="submit" class="update-button">更新</button>
                        <button type="button" onclick="closeModal()">キャンセル</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const textarea = document.getElementById('messageInput');

                if (!textarea) return;

                const key = 'chat_input_{{ $purchase->id }}';

                const saved = localStorage.getItem(key);
                if (saved) {
                    textarea.value = saved;
                }

                textarea.addEventListener('input', function() {
                    localStorage.setItem(key, textarea.value);
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const textarea = document.getElementById('messageInput');
                const form = document.getElementById('chatForm');

                if (!textarea || !form) return;

                const key = 'chat_input_{{ $purchase->id }}';

                form.addEventListener('submit', function() {
                    localStorage.removeItem(key);
                });
            });
        </script>
        <script>
            function editMessage(id, text) {
                const modal = document.getElementById('editModal');
                const textarea = document.getElementById('editMessageText');
                const form = document.getElementById('editForm');

                textarea.value = text;

                form.action = '/message/' + id;

                modal.style.display = 'flex';
            }

            function closeModal() {
                document.getElementById('editModal').style.display = 'none';
            }
        </script>

        @if (($isBuyer && $purchase->status === 'trading') || ($isSeller && $purchase->status === 'paid' && !$hasReviewed))
            <div class="review-modal" id="reviewModal">
                <div class="review-modal__content">
                    <div class="review-modal__header">
                        <h2>
                            @if ($isBuyer)
                                取引が完了しました。
                            @elseif ($isSeller)
                                購入者の評価をお願いします。
                            @endif
                        </h2>
                    </div>

                    <div class="review-modal__body">
                        <p>
                            @if ($isBuyer)
                                今回の取引相手はどうでしたか？
                            @elseif ($isSeller)
                                今回の購入者を評価してください。
                            @endif
                        </p>

                        <form action="{{ route('purchase.review.store', $purchase->id) }}" method="POST">
                            @csrf

                            <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 0) }}">

                            <div class="review-stars" id="reviewStars">
                                <span class="review-star" data-value="1">★</span>
                                <span class="review-star" data-value="2">★</span>
                                <span class="review-star" data-value="3">★</span>
                                <span class="review-star" data-value="4">★</span>
                                <span class="review-star" data-value="5">★</span>
                            </div>

                            @error('rating')
                                <p class="review-error">{{ $message }}</p>
                            @enderror

                            <div class="review-modal__footer">
                                <button type="submit" class="review-submit">送信する</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if (($isBuyer && $purchase->status === 'trading') || ($isSeller && $purchase->status === 'paid' && !$hasReviewed))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const openButton = document.getElementById('openReviewModal');
                    const modal = document.getElementById('reviewModal');
                    const stars = document.querySelectorAll('.review-star');
                    const ratingInput = document.getElementById('ratingInput');

                    if (openButton && modal) {
                        openButton.addEventListener('click', function() {
                            modal.classList.add('is-open');
                        });
                    }

                    if (modal) {
                        modal.addEventListener('click', function(e) {
                            if (e.target === modal) {
                                modal.classList.remove('is-open');
                            }
                        });
                    }

                    function updateStars(value) {
                        stars.forEach(star => {
                            const starValue = Number(star.dataset.value);
                            if (starValue <= value) {
                                star.classList.add('is-active');
                            } else {
                                star.classList.remove('is-active');
                            }
                        });
                    }

                    stars.forEach(star => {
                        star.addEventListener('click', function() {
                            const value = Number(this.dataset.value);
                            ratingInput.value = value;
                            updateStars(value);
                        });
                    });

                    updateStars(Number(ratingInput.value));

                    @if ($isSeller && $purchase->status === 'paid' && !$hasReviewed)
                        modal.classList.add('is-open');
                    @endif
                });
            </script>
        @endif

        @if ($errors->has('rating'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('reviewModal');
                    if (modal) {
                        modal.classList.add('is-open');
                    }
                });
            </script>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const messageBox = document.getElementById('tradeMessages');

                if (!messageBox) return;

                function scrollToBottom() {
                    messageBox.scrollTop = messageBox.scrollHeight;
                }

                scrollToBottom();

                const images = messageBox.querySelectorAll('img');
                images.forEach(img => {
                    if (!img.complete) {
                        img.addEventListener('load', scrollToBottom);
                    }
                });
                setTimeout(scrollToBottom, 100);
            });
        </script>
    </div>
@endsection
