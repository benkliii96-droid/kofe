@extends('layouts.app')

@section('title', 'Главная — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Главная</h1>
    <p class="muted">Статистика за 3 дня ({{ $from->format('d.m.Y') }} — {{ $to->format('d.m.Y') }})</p>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Всего заказов</div>
            <div class="stat-value">{{ $totalCount }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Общая сумма</div>
            <div class="stat-value">{{ number_format($totalSum, 2, ',', ' ') }} ₽</div>
        </div>
    </div>

    <h2>По пользователям</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Пользователь</th>
                <th>Количество заказов</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            @forelse($byUser as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['count'] }}</td>
                    <td>{{ number_format($row['sum'], 2, ',', ' ') }} ₽</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Нет заказов за период</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
