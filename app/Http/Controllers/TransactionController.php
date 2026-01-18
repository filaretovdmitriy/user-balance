<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionsResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private readonly TransactionService $transactions)
    {
    }

    public function latestTransactions(Request $request)
    {
        $user = $request->user();

        $transactions = $this->transactions->latest($user, 5);

        return TransactionsResource::collection($transactions);
    }

    public function allTransactions(Request $request)
    {
        $user = $request->user();
        $search = $request->query('search');

        $transactions = $this->transactions->all($user, $search);

        return TransactionsResource::collection($transactions);
    }
}
