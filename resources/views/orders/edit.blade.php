@extends('layouts.app')

@section('title', 'Редактировать заказ — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Редактирование заказа {{ $order->number }}</h1>
    <form method="POST" action="{{ route('orders.update', $order) }}" class="form" id="order-form">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="client_id">Клиент</label>
            <select id="client_id" name="client_id" required {{ !$order->canEditItems() ? '' : '' }}>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id', $order->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Дата заказа</label>
            <input type="text" value="{{ $order->order_date->format('d.m.Y') }}" disabled>
        </div>
        <div class="form-group">
            <label>Дата отгрузки</label>
            <input type="text" value="{{ $order->shipment_date ? $order->shipment_date->format('d.m.Y') : '—' }}" disabled>
        </div>
        <div class="form-group">
            <label>Статус</label>
            <input type="text" value="{{ $order->statusLabel() }}" disabled>
        </div>

        <h2>Позиции @if(!$order->canEditItems()) <span class="muted">(можно менять только при статусе «Новый»)</span> @endif</h2>
        @if($order->canEditItems())
            <button type="button" class="btn btn-secondary" id="add-item">Добавить товар</button>
        @endif
        <table class="table" id="items-table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Фото</th>
                    <th>Ед.</th>
                    <th>Цена</th>
                    <th>Скидка %</th>
                    <th>Кол-во</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <button type="submit" class="btn btn-primary">Редактировать</button>
        <a href="{{ route('orders.index') }}" class="btn btn-light">Назад</a>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/orders.js') }}"></script>
<script>
initOrderForm({
    apiUrl: '{{ route('api.products') }}',
    readonly: {{ $order->canEditItems() ? 'false' : 'true' }},
    items: [
        @foreach($order->items as $item)
        {
            product_id: {{ $item->product_id }},
            discount: {{ $item->discount }},
            quantity: {{ $item->quantity }}
        }@if(!$loop->last),@endif
        @endforeach
    ]
});
</script>
@endpush
