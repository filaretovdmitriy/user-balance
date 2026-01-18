<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionsResource;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function latestTransactions(Request $request)
    {
        $user = $request->user();

        $transactions = $user->transactions()->latest()->take(5)->get();

        return TransactionsResource::collection($transactions);
    }

    public function allTransactions(Request $request)
    {
        $user = $request->user();

        $transactions = $user->transactions()->get();

        return TransactionsResource::collection($transactions);
    }
}