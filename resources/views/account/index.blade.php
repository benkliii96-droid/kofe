@extends('layouts.app')

@section('title', 'Аккаунт — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Аккаунт</h1>

    <div class="info-block">
        <p><strong>ФИО:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Логин:</strong> {{ $user->login }}</p>
        <p><strong>Тип аккаунта:</strong> {{ $user->role === 'admin' ? 'Администратор' : 'Менеджер' }}</p>
    </div>

    <h2>Смена пароля</h2>
    <form method="POST" action="{{ route('account.password') }}" class="form form-narrow">
        @csrf
        <div class="form-group">
            <label for="current_password">Текущий пароль</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="password">Новый пароль</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Подтверждение пароля</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary">Сменить пароль</button>
    </form>
</div>
@endsection
