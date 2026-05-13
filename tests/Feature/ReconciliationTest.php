<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReconciliationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_reconciliation_with_surplus(): void
    {
        Transaction::factory()->create(['type' => 'income', 'amount' => 500, 'date' => now()->format('Y-m-d')]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 200, 'date' => now()->format('Y-m-d')]);

        $response = $this->postJson('/api/reconciliation', [
            'counted' => 350,
            'note' => 'Corte del día',
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['type' => 'reconciliation'])
            ->assertJsonPath('amount', fn($v) => (float) $v === 50.0);

        $this->assertDatabaseHas('transactions', [
            'type' => 'reconciliation',
            'amount' => 50,
        ]);
    }

    public function test_store_creates_reconciliation_with_shortage(): void
    {
        Transaction::factory()->create(['type' => 'income', 'amount' => 500, 'date' => now()->format('Y-m-d')]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 200, 'date' => now()->format('Y-m-d')]);

        $response = $this->postJson('/api/reconciliation', [
            'counted' => 250,
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['type' => 'reconciliation'])
            ->assertJsonPath('amount', fn($v) => (float) $v === -50.0);
    }

    public function test_store_accounts_for_previous_reconciliation(): void
    {
        Transaction::factory()->create(['type' => 'income', 'amount' => 500, 'date' => now()->format('Y-m-d')]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 200, 'date' => now()->format('Y-m-d')]);
        // Previous reconciliation: +50 surplus
        Transaction::factory()->create(['type' => 'reconciliation', 'amount' => 50, 'date' => now()->subDay()->format('Y-m-d')]);

        // Expected: 500 - 200 + 50 = 350
        $response = $this->postJson('/api/reconciliation', [
            'counted' => 370,
        ]);

        $response->assertCreated()
            ->assertJsonPath('amount', fn($v) => (float) $v === 20.0);
    }

    public function test_store_returns_exact_when_balance_matches(): void
    {
        Transaction::factory()->create(['type' => 'income', 'amount' => 300, 'date' => now()->format('Y-m-d')]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 100, 'date' => now()->format('Y-m-d')]);

        $response = $this->postJson('/api/reconciliation', [
            'counted' => 200,
        ]);

        $response->assertOk()
            ->assertJsonFragment(['difference' => 0]);
    }

    public function test_store_validates_counted_required(): void
    {
        $response = $this->postJson('/api/reconciliation', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['counted']);
    }

    public function test_store_validates_counted_min_zero(): void
    {
        $response = $this->postJson('/api/reconciliation', [
            'counted' => -1,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['counted']);
    }

    public function test_index_returns_reconciliation_history(): void
    {
        Transaction::factory()->count(3)->create(['type' => 'reconciliation']);

        $response = $this->getJson('/api/reconciliation');

        $response->assertOk()
            ->assertJsonStructure(['data', 'current_page', 'last_page']);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_index_returns_empty_when_no_reconciliations(): void
    {
        $response = $this->getJson('/api/reconciliation');

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }

    public function test_dashboard_includes_reconciliation_in_balance(): void
    {
        $today = now()->format('Y-m-d');
        Transaction::factory()->create(['type' => 'income', 'amount' => 1000, 'date' => $today]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 300, 'date' => $today]);
        Transaction::factory()->create(['type' => 'reconciliation', 'amount' => 50, 'date' => $today]);

        $response = $this->getJson('/api/dashboard');

        $response->assertOk();
        $this->assertEquals(750, (float) $response->json('balance'));
        $this->assertEquals(50, (float) $response->json('reconciliation_total'));
    }

    public function test_dashboard_returns_last_reconciliation(): void
    {
        Transaction::factory()->create(['type' => 'reconciliation', 'amount' => 100, 'date' => now()->format('Y-m-d')]);

        $response = $this->getJson('/api/dashboard');

        $response->assertOk();
        $this->assertNotNull($response->json('last_reconciliation'));
        $this->assertEquals(100, (float) $response->json('last_reconciliation.amount'));
    }
}
