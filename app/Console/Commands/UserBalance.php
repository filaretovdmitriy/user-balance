<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Console\Command;

class UserBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:balance 
        {--name: Логин пользователя}
        {--action: debit|credit}
        {--amount: Сумма}
        {--description: Optional Описание транзакции}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User balance debit or credit transaction';

    /**
     * Execute the console command.
     */
    public function handle(TransactionService $transactionService)
    {
        $name = $this->ask('Логин (email) пользователя');
        $action = $this->choice('Действие', ['credit', 'debit'], 0);
        $amount = (float) $this->ask('Сумма');
        $description = $this->ask('Описание (необязательно)', '');

        $user = User::query()->where('name', $name)->first();
        if (! $user) {
            $this->error('Пользователь не найден');
            return self::FAILURE;
        }

        try {
            $trx = $action === 'credit'
                ? $transactionService->credit($user, $amount, $description)
                : $transactionService->debit($user, $amount, $description);

            $this->info("Операция списания\зачисления прошла успешно");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}