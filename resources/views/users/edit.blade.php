@extends('layouts.app')

@section('title', 'Редактировать пользователя — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Редактирование пользователя</h1>
    <form method="POST" action="{{ route('users.update', $user) }}" class="form form-narrow">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">ФИО</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="form-group">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" value="{{ old('login', $user->login) }}" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль (оставьте пустым, чтобы не менять)</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Подтверждение пароля</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
        <div class="form-group">
            <label for="role">Тип аккаунта</label>
            <select id="role" name="role" required>
                <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Менеджер</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Администратор</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить отредактированное</button>
        <a href="{{ route('users.index') }}" class="btn btn-light">Назад</a>
    </form>
</div>
@endsection
