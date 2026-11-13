@extends('layouts.app')

@section('title', 'Клиенты — Кофеманы')

@section('content')
<div class="page fade-in">
    <div class="page-header">
        <h1>Клиенты</h1>
        <div class="actions">
            <a href="{{ route('clients.create') }}" class="btn btn-primary">Добавить клиента</a>
            <a href="{{ route('clients.import') }}" class="btn btn-secondary">Загрузить из CSV</a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ФИО</th>
                <th>Email</th>
                <th>Адрес доставки</th>
                <th>Контактный номер</th>
                <th>Пометка</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($clients as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->address }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->note }}</td>
                    <td>
                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-small">Редактировать</a>
                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline-form" onsubmit="return confirm('Удалить клиента?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-small btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Клиентов нет</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
