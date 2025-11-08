@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('button')
    <input class="input__txt" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
    <nav class="header__nav">
        <ul>
            <li class="logout__button"><a href="{{ route('login')}}">ログアウト</a></li>
            <li class="mypage__button"><a href="">マイページ</a></li>
            <li class="sell__button" ><a href="">出品</a></li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="form__content">
    <div class="form__heading">
        <h2>プロフィール設定</h2>
    </div>
    <form class="form" action="/mypage" method="get" novalidate>
        @csrf
        <div class="profile__flex">
            <img src="" alt="">
            <a href="">画像を選択する</a>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text name-inputs">
                    <input type="text" name="name" value="{{ old('name') }}" />
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">郵便番号</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="post-code" name="post-code" value="{{ old('post-code') }}" />
                    </div>
                    <div class="form__error">
                        @error('post-code')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">住所</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="address" name="address" />
                    </div>
                    <div class="form__error">
                        @error('address')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">建物名</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="building" name="building" />
                    </div>
                    <div class="form__error">
                        @error('building')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">更新する</button>
            </div>
    </form>
</div>
@endsection