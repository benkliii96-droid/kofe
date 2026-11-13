@extends('layouts.app')

@section('title', 'Добавить пользователя — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Добавление пользователя</h1>
    <form method="POST" action="{{ route('users.store') }}" class="form form-narrow">
        @csrf
        <div class="form-group">
            <label for="name">ФИО</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Подтверждение пароля</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="form-group">
            <label for="role">Тип аккаунта</label>
            <select id="role" name="role" required>
                <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Менеджер</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Администратор</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
        <a href="{{ route('users.index') }}" class="btn btn-light">Назад</a>
    </form>
</div>
@endsection
