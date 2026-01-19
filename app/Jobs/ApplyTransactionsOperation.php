<?php

namespace App\Jobs;

use App\Models\Balance;
use App\Models\Transaction;
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
            $balance = Balance::query()
                ->where('user_id', $this->userId)
                ->lockForUpdate()
                ->first();

            if (! $balance) {
                $balance = Balance::create([
                    'user_id' => $this->userId,
                    'amount' => '0.00',
                ]);

                $balance = Balance::query()
                   ->whereKey($balance->id)
                   ->lockForUpdate()
                   ->firstOrFail();
            }

            $balanceCents = (int) str_replace('.', '', number_format((float) $balance->amount, 2, '.', ''));
            $amountCents  = (int) str_replace('.', '', number_format((float) $this->amount, 2, '.', ''));

            if ($this->type === 'debit') {
                if ($balanceCents < $amountCents) {
                    throw new RuntimeException('Не хватает баланса для списания :( ');
                }

                $balance->decrement('amount', $this->amount);
            } else {
                $balance->increment('amount', $this->amount);
            }

            Transaction::create([
                'user_id' => $this->userId,
                'amount' => $this->amount,
                'type' => $this->type,
                'description' => $this->description,
            ]);
        });
    }
}