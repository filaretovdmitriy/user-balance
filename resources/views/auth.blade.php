@extends('layouts.app')

@section('title', 'Аутентификация')

@section('content')

    <h1>Аутентификация</h1>

    <div class="py-4">Описание страницы</div>
    
    <div data-vue="auth-form"></div>
    @push('scripts')
        @vite('resources/js/features/AuthForm/AuthForm.js')
    @endpush

@endsection