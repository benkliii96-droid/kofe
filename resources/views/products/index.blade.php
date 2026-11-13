@extends('layouts.app')

@section('title', 'Товары — Кофеманы')

@section('content')
<div class="page fade-in">
    <div class="page-header">
        <h1>Товары</h1>
        <div class="actions">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Добавить товар</a>
            <a href="{{ route('products.import') }}" class="btn btn-secondary">Загрузить товары из CSV</a>
            <a href="{{ route('characteristics.create') }}" class="btn btn-secondary">Добавить дополнительную характеристику</a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Наименование товара</th>
                <th>Производитель</th>
                <th>Стоимость</th>
                <th>Единица измерения</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->manufacturer }}</td>
                    <td>{{ number_format($product->price, 2, ',', ' ') }} ₽</td>
                    <td>{{ $product->unit }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-small">Редактировать</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-form" onsubmit="return confirm('Удалить товар?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-small btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Товаров нет</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
