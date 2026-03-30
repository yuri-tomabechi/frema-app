<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>COATHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                <img src="{{ asset('images/logo.svg') }}" alt="">
            </a>
            <div class="header__right">
                <form action="{{ route('item.search') }}" method="GET">
                    <input class="input__txt" type="text" name="keyword" placeholder="なにをお探しですか？"
                        value="{{ session('keyword') }}">
                    <input type="hidden" name="page" value="{{ request()->is('mylist*') ? 'mylist' : 'items' }}">

                </form>
                <nav class="header__nav">
                    <ul>
                        @guest
                            {{-- 未ログイン時 --}}
                            <li class="login"><a href="{{ route('login') }}">ログイン</a></li>
                            <li><a href="{{ route('register') }}">新規登録</a></li>
                        @endguest
                        @auth
                            <li class="logout__button"><a href="/mylogout">ログアウト</a></li>
                            <li class="mypage__button"><a href="/mypage?page=sell">マイページ</a></li>
                            <li class="sell__button"><a href="/sell">出品</a></li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>
