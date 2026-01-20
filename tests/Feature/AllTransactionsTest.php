<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AllTransactionsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
     use RefreshDatabase;

    public function test_guest_cannot_access_all_transactions(): void
    {
        $this->getJson('/api/transactions')
            ->assertStatus(401);
    }

    public function test_authenticated_user_can_get_all_transactions(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']); 

        Transaction::factory()->count(4)->create(['user_id' => $user->id]);

        $this->getJson('/api/transactions')
            ->assertOk()
            ->assertJsonCount(4, 'data');
    }

    public function test_search_is_validated_max_255(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']); 

        $this->getJson('/api/transactions?search=' . str_repeat('a', 256))
            ->assertStatus(422);
    }

    public function test_search_filters_transactions_by_description_example(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']); 

        Transaction::factory()->create([
            'user_id' => $user->id,
            'description' => 'Coffee',
        ]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'description' => 'Rent',
        ]);

        $response = $this->getJson('/api/transactions?search=cof');
        $response->assertOk();

        $descriptions = collect($response->json('data'))->pluck('description')->all();
        $this->assertContains('Coffee', $descriptions);
        $this->assertNotContains('Rent', $descriptions);
    }
}