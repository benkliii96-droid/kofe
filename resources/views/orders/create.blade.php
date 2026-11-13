@extends('layouts.app')

@section('title', 'Новый заказ — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Новый заказ</h1>
    <form method="POST" action="{{ route('orders.store') }}" class="form" id="order-form">
        @csrf
        <div class="form-group">
            <label for="client_id">Клиент</label>
            <select id="client_id" name="client_id" required>
                <option value="">Выберите клиента</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Дата заказа</label>
            <input type="text" value="{{ now()->format('d.m.Y') }}" disabled>
        </div>
        <div class="form-group">
            <label>Статус</label>
            <input type="text" value="Новый" disabled>
        </div>

        <h2>Позиции</h2>
        <button type="button" class="btn btn-secondary" id="add-item">Добавить товар</button>
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

        <button type="submit" class="btn btn-primary">Создать</button>
        <a href="{{ route('orders.index') }}" class="btn btn-light">Назад</a>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/orders.js') }}"></script>
<script>
initOrderForm({
    apiUrl: '{{ route('api.products') }}',
    items: []
});
</script>
@endpush
