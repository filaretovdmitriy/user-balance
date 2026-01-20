<?php

namespace App\Http\Controllers;

use App\Http\Requests\AllTransactionsRequest;
use App\Http\Resources\TransactionsResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionController extends Controller
{
    public function __construct(private readonly TransactionService $transactions)
    {
    }

    public function latestTransactions(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $transactions = $this->transactions->latest($user, 5);

        return TransactionsResource::collection($transactions);
    }

    public function allTransactions(AllTransactionsRequest $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $data = $request->validated();
        $search = $data['search'] ?? null;

        $transactions = $this->transactions->all($user, $search);

        return TransactionsResource::collection($transactions);
    }
}
