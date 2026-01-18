<?php

namespace App\Services;

use App\Jobs\ApplyTransactionsOperation;
use App\Models\Transaction;
use App\Models\User;

class TransactionService
{
    public function credit(User $user, string $amount, ?string $description): Bool
    {
        ApplyTransactionsOperation::dispatch($user->id, 'credit', $amount, $description)
            ->afterCommit();

        return true;
    }

    public function debit(User $user, string $amount, ?string $description): Bool
    {
        ApplyTransactionsOperation::dispatch($user->id, 'debit', $amount, $description)
           ->afterCommit();

        return true;
    }
}