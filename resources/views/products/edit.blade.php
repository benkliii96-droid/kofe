@extends('layouts.app')

@section('title', 'Редактировать товар — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Редактирование товара</h1>
    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="form">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Наименование товара</label>
            <input type="text" value="{{ $product->name }}" disabled>
        </div>
        <div class="form-group">
            <label for="manufacturer">Производитель товара</label>
            <input type="text" id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $product->manufacturer) }}" required>
        </div>
        <div class="form-group">
            <label>Текущее фото</label>
            <div>
                <img src="{{ $product->photo ? asset('storage/'.$product->photo) : asset('images/placeholder.png') }}" alt="" class="thumb">
            </div>
            <label for="photo">Новая фотография (JPG/JPEG, макс 2МБ)</label>
            <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,image/jpeg">
        </div>
        <div class="form-group">
            <label for="price">Стоимость товара</label>
            <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="form-group">
            <label>Единица измерения</label>
            <input type="text" value="{{ $product->unit }}" disabled>
        </div>
        <div class="form-group">
            <label for="short_description">Краткое описание</label>
            <input type="text" id="short_description" name="short_description" maxlength="255" value="{{ old('short_description', $product->short_description) }}" required>
        </div>
        <div class="form-group">
            <label for="full_description">Полное описание</label>
            <textarea id="full_description" name="full_description" rows="4" required>{{ old('full_description', $product->full_description) }}</textarea>
        </div>

        <h3>Дополнительные характеристики</h3>
        <div id="chars">
            @php $pivot = $product->characteristics; @endphp
            @forelse($pivot as $char)
                <div class="char-row">
                    <select name="char_id[]">
                        <option value="">—</option>
                        @foreach($characteristics as $c)
                            <option value="{{ $c->id }}" {{ $c->id === $char->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="char_value[]" value="{{ $char->pivot->value }}" placeholder="Значение характеристики">
                </div>
            @empty
                <div class="char-row">
                    <select name="char_id[]">
                        <option value="">—</option>
                        @foreach($characteristics as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="char_value[]" placeholder="Значение характеристики">
                </div>
            @endforelse
        </div>
        <button type="button" class="btn btn-secondary" id="add-char">Добавить характеристику</button>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('products.index') }}" class="btn btn-light">Назад</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const charOptions = `@foreach($characteristics as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach`;
document.getElementById('add-char').addEventListener('click', function () {
    const row = document.createElement('div');
    row.className = 'char-row';
    row.innerHTML = `<select name="char_id[]"><option value="">—</option>${charOptions}</select><input type="text" name="char_value[]" placeholder="Значение характеристики">`;
    document.getElementById('chars').appendChild(row);
});
</script>
@endpush
