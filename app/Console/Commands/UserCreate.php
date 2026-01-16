<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create  {--name= : Имя} {--email= : Email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание нового пользователя';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $name = $this->option('name') ?: $this->ask('Имя');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->secret('Пароль (не будет отображаться)');

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("Пользователь создан: {$email}");

        return self::SUCCESS;
    }
}
