@extends('layouts.app')

@section('title', 'Аутентификация')

@section('content')

<h1>Аутентификация</h1>

<div class="py-4">Войдите под учетными данными пользователя тестового задания :). Информация о создании пользователя
    описана в README.md</div>

<div data-vue="auth-form"></div>
@push('scripts')
@vite('resources/js/features/AuthForm/AuthForm.js')
@endpush

@endsection