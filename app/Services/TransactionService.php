<?php

namespace App\Services;

use App\Jobs\ApplyTransactionsOperation;
use App\Models\User;
use Illuminate\Support\Collection;

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

    public function latest(User $user, int $limit = 5): Collection
    {
        return $user->transactions()
            ->latest()
            ->take($limit)
            ->get();
    }

    public function all(User $user, ?string $search = null): Collection
    {
        return $user->transactions()
            ->when($search, function ($query, $search) {
                $query->where('description', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();
    }
}
