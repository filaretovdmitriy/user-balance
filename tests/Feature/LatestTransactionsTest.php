<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LatestTransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_latest_transactions(): void
    {
        $this->getJson('/api/transactions/latest')
            ->assertStatus(401);
    }

    public function test_authenticated_user_gets_max_5_latest_transactions(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        Transaction::factory()->count(7)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/transactions/latest');

        $response->assertOk();

        $response->assertJsonCount(5, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'amount', 'type', 'description', 'created_at'],
            ],
        ]);
    }

    public function test_latest_transactions_do_not_include_other_users_data(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Transaction::factory()->count(3)->create(['user_id' => $user->id]);
        Transaction::factory()->count(3)->create(['user_id' => $other->id]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/transactions/latest');
        $response->assertOk();

        $returnedIds = collect($response->json('data'))->pluck('id')->all();

        $this->assertCount(
            3,
            Transaction::query()->whereIn('id', $returnedIds)->where('user_id', $user->id)->get()
        );
    }
}
