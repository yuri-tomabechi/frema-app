@extends('layouts.profile')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
    <div class="list">
        <div class="list__flex">
            <a class="black left" href="/">おすすめ</a>
            <a class="red" href="">マイリスト</a>
        </div>
    </div>
    <section>
        @if ($items->isEmpty())
            <p>まだマイリストはありません。</p>
        @else
            <div class="item">
                <ul class="item__flex">
                    @foreach ($items as $item)
                        <li>
                            <a href="{{ route('item.detail', $item->id) }}">
                                @if ($item->is_sold)
                                    <span class="sold-badge">SOLD</span>
                                @endif
                                <img src="{{ asset('storage/' . $item->item_url) }}" alt="{{ $item->name }}">
                                <p>{{ $item->name }}</p>
                            </a>
                        </li>
                    @endforeach

                </ul>
            </div>
        @endif
    </section>

@endsection
