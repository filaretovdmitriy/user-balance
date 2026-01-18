<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ApplyTransactionsOperation implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $userId,
        public string $type,
        public string $amount,
        public ?string $description,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $user = User::query()->whereKey($this->userId)->lockForUpdate()->firstOrFail();

            if ($this->type === 'debit') {
                if (bccomp((string)$user->balance, (string)$this->amount, 2) < 0) {
                    throw new RuntimeException('Не хватает баланса для списания :( ');
                }
                $user->decrement('balance', $this->amount);
            } else {
                $user->increment('balance', $this->amount);
            }

            Transaction::create([
                'user_id' => $user->id,
                'amount' => $this->amount,
                'type' => $this->type,
                'description' => $this->description,
            ]);
        });
    }
}