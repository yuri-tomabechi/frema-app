@extends('layouts.profile')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
    <div class="sell__page">
        <h1>商品の出品</h1>
        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="item__img">
                <h3>商品画像</h3>
                <div class="img">
                    <input type="file" name="item_url" id="image" style="display:none;">
                    <label for="image" id="fileSelectBtn" class="upload-btn">画像を選択する</label>
                    <div class="preview">
                        <img id="previewImage" src="" alt=""
                            style="display:none; max-width: 200px; margin-top: 20px;">
                    </div>
                </div>
                <div class="form__error">
                    @error('item_url')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="item__detail">
                <h2>商品の詳細</h2>
                <div class="category">
                    <h3>カテゴリー</h3>
                    <div class="category-list">
                        @foreach ($categories as $category)
                            <label class="category-item">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="form__error">
                        @error('categories')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="condition">
                    <h3>商品の状態</h3>
                    <select name="condition">
                        <option value="" disabled {{ old('condition') ? '' : 'selected' }}>選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                    <div class="form__error">
                        @error('condition')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <h2>商品名と詳細</h2>
                <div class="form__group">
                    <div class="form__group-title">
                        <h3 class="form__label--item">商品名</h3>
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
                            <h3 class="form__label--item">ブランド名</h3>
                        </div>
                        <div class="form__group-content">
                            <div class="form__input--text">
                                <input type="text" name="brand_name" value="{{ old('brand_name') }}" />
                            </div>
                            <div class="form__error">
                                @error('brand_name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form__group">
                        <div class="form__group-title">
                            <h3 class="form__label--item">商品の説明</h3>
                        </div>
                        <div class="form__group-content">
                            <div class="form__input--text">
                                <textarea name="description" rows="7">{{ old('description') }}</textarea>
                            </div>
                            <div class="form__error">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form__group">
                        <div class="form__group-title">
                            <h3 class="form__label--item">販売価格</h3>
                        </div>
                        <div class="form__group-content">
                            <div class="form__input--text">
                                <input type="number" name="price" value="{{ old('price') }}" />
                            </div>
                            <div class="form__error">
                                @error('price')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form__button">
                        <button class="form__button-submit" type="submit">出品する</button>
                    </div>

                </div>
                <script>
                    document.getElementById('image').addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = document.getElementById('previewImage');
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };

                        reader.readAsDataURL(file);
                    });
                </script>

        </form>
    </div>
@endsection
