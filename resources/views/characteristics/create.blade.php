@extends('layouts.app')

@section('title', 'Характеристики — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Добавить характеристику</h1>
    <form method="POST" action="{{ route('characteristics.store') }}" class="form form-narrow">
        @csrf
        <div class="form-group">
            <label for="name">Название характеристики</label>
            <input type="text" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
        <a href="{{ route('products.index') }}" class="btn btn-light">Назад</a>
    </form>

    <h2>Существующие характеристики</h2>
    <ul>
        @forelse($characteristics as $c)
            <li>{{ $c->name }}</li>
        @empty
            <li>Пока нет</li>
        @endforelse
    </ul>
</div>
@endsection
