<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->load('balance');
});

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/transactions/latest', [TransactionController::class, 'latestTransactions']);
Route::middleware('auth:sanctum')->get('/transactions', [TransactionController::class, 'allTransactions']);
