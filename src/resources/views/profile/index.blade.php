@extends('layouts.profile')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="form__content">
    <div class="form__heading">
        <h2>プロフィール設定</h2>
    </div>
    <form class="form" action="{{ route('profile.update') }}" method="post" novalidate enctype="multipart/form-data">
        @csrf
        <div class="profile__flex">
            <img src="" alt="">
            <input type="file" name="icon_url" id="image" style="display:none;">
            <label for="image" id="fileSelectBtn" class="upload-btn">画像を選択する</label>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text name-inputs">
                    <input type="text" name="name" value="{{ old('name'), $user->name}}" />
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
                        <input type="text" name="post_code" pattern="^\d{3}-\d{4}$" placeholder="123-4567" value="{{ old('post_code'), $user->post_code }}" />
                    </div>
                    <div class="form__error">
                        @error('post_code')
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
                        <input type="address" name="address" value="{{ old('address', $user->address) />
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
                        <input type="building" name="building" value="{{ old('building', $user->building) }}"/>
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