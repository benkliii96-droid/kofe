@extends('layouts.app')

@section('title', 'Авторизация — Кофеманы')

@section('content')
<div class="login-page">
    <div class="login-card fade-in">
        <div class="login-brand">
            <img src="{{ asset('images/logo.svg') }}" alt="Кофеманы" width="72" height="72">
            <h1>Кофеманы</h1>
            <p>Система учета продаж</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="form">
            @csrf
            <div class="form-group">
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Войти</button>
        </form>
    </div>
</div>
@endsection
