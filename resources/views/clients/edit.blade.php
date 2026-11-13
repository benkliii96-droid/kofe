@extends('layouts.app')

@section('title', 'Редактировать клиента — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Редактирование клиента</h1>
    <form method="POST" action="{{ route('clients.update', $client) }}" class="form form-narrow">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">ФИО</label>
            <input type="text" id="name" name="name" value="{{ old('name', $client->name) }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $client->email) }}" required>
        </div>
        <div class="form-group">
            <label for="address">Адрес доставки</label>
            <input type="text" id="address" name="address" value="{{ old('address', $client->address) }}" required>
        </div>
        <div class="form-group">
            <label for="phone">Контактный номер</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $client->phone) }}" required>
        </div>
        <div class="form-group">
            <label for="note">Пометка</label>
            <textarea id="note" name="note" rows="3">{{ old('note', $client->note) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('clients.index') }}" class="btn btn-light">Назад</a>
    </form>
</div>
@endsection
