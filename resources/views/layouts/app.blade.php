<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Кофеманы')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    @auth
    <header class="header">
        <div class="header-inner">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.svg') }}" alt="Кофеманы" width="40" height="40">
                <span>Кофеманы</span>
            </a>
            <nav class="nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Главная</a>
                <a href="{{ route('account') }}" class="{{ request()->routeIs('account') ? 'active' : '' }}">Аккаунт</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">Пользователи</a>
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') || request()->routeIs('characteristics.*') ? 'active' : '' }}">Товары</a>
                @endif
                <a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">Клиенты</a>
                <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">Заказы</a>
            </nav>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn btn-light">Выйти</button>
            </form>
        </div>
    </header>
    @endauth

    <main class="main @auth main-auth @endauth">
        @if(session('success'))
            <div class="alert alert-success fade-in">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error fade-in">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
