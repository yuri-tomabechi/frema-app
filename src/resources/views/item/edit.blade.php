@extends('layouts.profile')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="form__content">
      <div class="form__heading">
        <h2>住所の変更</h2>
      </div>
      <form class="form" action="{{ route('purchase.address.update', $item->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="form__group">
          <div class="form__group-title">
            <h4 class="form__label--item">郵便番号</h4>
          </div>
          <div class="form__group-content">
            <div class="form__input--text name-inputs">
              <input type="text" name="post_code" pattern="^\d{3}-\d{4}$" placeholder="123-4567" value="{{ old('post_code', $user->post_code) }}" />
            </div>
            <div class="form__error">
                @error('post_code')
                    {{ $message }} 
                @enderror
            </div>
          </div>
        </div>
        <div class="form__group">
        <div class="form__group">
          <div class="form__group-title">
            <h4 class="form__label--item">住所</h4>
          </div>
          <div class="form__group-content">
            <div class="form__input--text">
              <input type="text" name="address"  value="{{ old('address', $user->address)}}"/>
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
                <h4 class="form__label--item">建物名</h4>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{ old('building', $user->building)}}" />
                </div>
            </div>
        </div>
        <div class="form__button">
          <button class="form__button-submit" type="submit">更新する</button>
        </div>
      </form>
    </div>

@endsection