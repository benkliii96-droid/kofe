@extends('layouts.app')

@section('title', 'Пользователи — Кофеманы')

@section('content')
<div class="page fade-in">
    <div class="page-header">
        <h1>Пользователи</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Добавить пользователя</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ФИО</th>
                <th>Логин</th>
                <th>Email</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->login }}</td>
                    <td>{{ $user->email }}</td>
                    <td><a href="{{ route('users.edit', $user) }}" class="btn btn-small">Редактировать</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
