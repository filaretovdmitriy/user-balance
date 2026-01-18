<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->decimal('amount', 14, 2)->default(0);
            $table->timestamps();
        });

        // Тут я, конечно, оплошал в предыдущей миграции, поэтому исправляю )
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'balance')) {
                $table->dropColumn('balance');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
