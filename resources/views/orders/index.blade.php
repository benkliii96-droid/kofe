@extends('layouts.app')

@section('title', 'Заказы — Кофеманы')

@section('content')
<div class="page fade-in">
    <div class="page-header">
        <h1>Заказы</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">Новый заказ</a>
    </div>

    <form method="GET" action="{{ route('orders.index') }}" class="filters">
        <select name="status">
            <option value="">Все статусы</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="user_id">
            <option value="">Все пользователи</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
        <select name="client_id">
            <option value="">Все клиенты</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
            @endforeach
        </select>
        <input type="date" name="order_date" value="{{ request('order_date') }}" title="Дата создания">
        <input type="date" name="shipment_date" value="{{ request('shipment_date') }}" title="Дата отгрузки">
        <button type="submit" class="btn btn-secondary">Фильтр</button>
        <a href="{{ route('orders.index') }}" class="btn btn-light">Сброс</a>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Номер</th>
                <th>Клиент</th>
                <th>Пользователь</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Дата создания</th>
                <th>Дата отгрузки</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->number }}</td>
                    <td>{{ $order->client->name }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ number_format($order->total, 2, ',', ' ') }} ₽</td>
                    <td>{{ $order->statusLabel() }}</td>
                    <td>{{ $order->order_date->format('d.m.Y') }}</td>
                    <td>{{ $order->shipment_date ? $order->shipment_date->format('d.m.Y') : '—' }}</td>
                    <td class="actions-cell">
                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-small">Редактировать</a>
                        @if($order->status !== 'cancelled')
                            <form method="POST" action="{{ route('orders.status', $order) }}" class="inline-form status-form">
                                @csrf
                                <select name="status" onchange="this.form.submit()">
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}
                                            @if(!$order->canChangeTo($key)) disabled @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline-form" onsubmit="return confirm('Отменить заказ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small btn-danger">Отменить</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Заказов нет</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
