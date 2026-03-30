@extends('layouts.profile')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    <div class="purchase__flex">
        <div class="flex__left">
            <div class="item">
                <div class="item__img purchase__img">
                    <img src="{{ asset('storage/' . $item->item_url) }}" alt="">
                </div>
                <div class="item__detail">
                    <h2 class="item__name">{{ $item->name }}</h2>
                    <p class="item__price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>
            <form action="{{ route('purchase.checkout', $item->id) }}" method="POST" id="paymentForm">
                @csrf
                <div class="pay__option">
                    <h3>支払い方法</h3>
                    <select name="payment" id="paymentSelect" required>
                        <option value="" selected disabled>選択してください</option>
                        <option value="convenience">コンビニ払い</option>
                        <option value="card">カード支払い</option>
                    </select>
                    <div class="form__error">
                        @error('payment')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="delivery">
                    <div class="delivery__flex">
                        <h3>配送先</h3>
                        <a href="{{ route('purchase.address.edit', $item->id) }}" class="">変更する</a>
                    </div>
                    <p>〒{{ $purchase->post_code ?? $user->post_code }}<br>{{ $purchase->address ?? $user->address }}{{ $purchase->building ?? $user->building }}
                    </p>
                </div>

        </div>
        <div class="flex__right">
            <div class="summary__price">
                <p>商品代金</p>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
            <div class="summary__payment">
                <p class="howto">支払い方法</p>
                <p id="paymentSummary">未選択</p>
            </div>
            <button href="" class="purchase__btn">購入する</button>
            </form>
        </div>
    </div>
    <script>
        const paymentSelect = document.getElementById('paymentSelect');
        const paymentSummary = document.getElementById('paymentSummary');

        function updatePaymentSummary() {
            const selectedOption = paymentSelect.options[paymentSelect.selectedIndex];
            if (!selectedOption || selectedOption.value === '') {
                paymentSummary.textContent = '未選択';
            } else {
                paymentSummary.textContent = selectedOption.text;
            }
        }

        // 初期表示
        updatePaymentSummary();

        // 変更時
        paymentSelect.addEventListener('change', updatePaymentSummary);

        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const payment = document.getElementById('paymentSelect').value;

            if (!payment) {
                alert('支払い方法を選択してください');
                e.preventDefault();
                return;
            }

            if (payment === 'card') {
                this.action = "{{ route('purchase.checkout', $item->id) }}";
            } else if (payment === 'convenience') {
                this.action = "{{ route('purchase.store', $item->id) }}";
            }
        });
    </script>
@endsection
