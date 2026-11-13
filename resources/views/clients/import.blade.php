@extends('layouts.app')

@section('title', 'Импорт клиентов — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Загрузка клиентов из CSV</h1>
    <form method="POST" action="{{ route('clients.import.store') }}" enctype="multipart/form-data" class="form form-narrow">
        @csrf
        <div class="form-group">
            <label for="file">Файл CSV</label>
            <input type="file" id="file" name="file" accept=".csv,.txt" required>
        </div>
        <button type="submit" class="btn btn-primary">Загрузить</button>
        <a href="{{ route('clients.index') }}" class="btn btn-light">Назад</a>
    </form>
</div>
@endsection
