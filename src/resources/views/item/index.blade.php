@extends('layouts.profile')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="list">
    <div class="list__flex">
        <a class="red left" href="">おすすめ</a>
        <a class="black" href="{{ route('like.index') }}">マイリスト</a>
    </div>
</div>
<section>
    <div class="item">
        <ul class="item__flex">
            @foreach($items as $item)
                <li>
                    <a href="{{ route('item.detail', $item->id) }}">
                        @if($item->is_sold)
                            <span class="sold-badge">SOLD</span>
                        @endif
                        <img src="{{ asset('storage/' . $item->item_url) }}" alt="{{ $item->name }}">
                        <p>{{ $item->name }}</p>
                    </a>
                </li>
            @endforeach

        </ul>
    </div>
</section>

@endsection