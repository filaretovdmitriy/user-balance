@extends('layouts.app')

@section('title', 'История операций')

@section('content')

    <h1>История операций</h1>

     <div data-vue="user-transactions"></div>
    @vite('resources/js/features/UserTransactions/UserTransactions.js')

@endsection