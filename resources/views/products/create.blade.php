@extends('layouts.app')

@section('title', 'Добавить товар — Кофеманы')

@section('content')
<div class="page fade-in">
    <h1>Добавление товара</h1>
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="form">
        @csrf
        <div class="form-group">
            <label for="name">Наименование товара</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="manufacturer">Производитель товара</label>
            <input type="text" id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}" required>
        </div>
        <div class="form-group">
            <label for="photo">Фотография (JPG/JPEG, макс 2МБ)</label>
            <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,image/jpeg">
        </div>
        <div class="form-group">
            <label for="price">Стоимость товара</label>
            <input type="number" step="0.01" id="price" name="price" value="{{ old('price') }}" required>
        </div>
        <div class="form-group">
            <label for="unit">Единица измерения</label>
            <select id="unit" name="unit" required>
                @foreach(['кг.','шт.','гр.','л.','мл.'] as $u)
                    <option value="{{ $u }}" {{ old('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="short_description">Краткое описание (до 255)</label>
            <input type="text" id="short_description" name="short_description" maxlength="255" value="{{ old('short_description') }}" required>
        </div>
        <div class="form-group">
            <label for="full_description">Полное описание</label>
            <textarea id="full_description" name="full_description" rows="4" required>{{ old('full_description') }}</textarea>
        </div>

        <h3>Дополнительные характеристики</h3>
        <div id="chars">
            @if($characteristics->count())
                @foreach($characteristics as $i => $char)
                    <div class="char-row">
                        <select name="char_id[]">
                            <option value="">—</option>
                            @foreach($characteristics as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="char_value[]" placeholder="Значение характеристики">
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-secondary" id="add-char">Добавить характеристику</button>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Добавить</button>
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
