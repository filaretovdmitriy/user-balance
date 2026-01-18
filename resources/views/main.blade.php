@extends('layouts.app')

@section('title', 'Пользователь')

@section('content')

    <h1>Пользователь</h1>

    <div data-vue="user-profile"></div>
    @vite('resources/js/features/UserProfile/UserProfile.js')

@endsection