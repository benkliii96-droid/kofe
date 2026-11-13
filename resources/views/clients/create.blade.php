@extends('layouts.app')

@section('title', 'Добавить клиента — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Добавление клиента</h1>
    <form method="POST" action="{{ route('clients.store') }}" class="form form-narrow">
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
            <label for="address">Адрес доставки</label>
            <input type="text" id="address" name="address" value="{{ old('address') }}" required>
        </div>
        <div class="form-group">
            <label for="phone">Контактный номер</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
        </div>
        <div class="form-group">
            <label for="note">Пометка</label>
            <textarea id="note" name="note" rows="3">{{ old('note') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
        <a href="{{ route('clients.index') }}" class="btn btn-light">Назад</a>
    </form>
</div>
@endsection
