<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TransactionService
{
    public function credit(User $user, string $amount, ?string $description): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();

            $trx = Transaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'credit',
                'description' => $description,
            ]);

            $lockedUser->increment('balance', $amount);

            return $trx;
        });
    }

    public function debit(User $user, string $amount, ?string $description): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            $updated = User::whereKey($user->id)
                ->where('balance', '>=', $amount)
                ->decrement('balance', $amount);

            if ($updated === 0) {
                throw new RuntimeException('No money :( ');
            }

            $trx = Transaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'debit',
                'description' => $description,
            ]);

            $lockedUser->decrement('balance', $amount);

            return $trx;
        });
    }
}
