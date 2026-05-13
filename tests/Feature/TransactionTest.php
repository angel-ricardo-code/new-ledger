<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_transactions(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);
        Transaction::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/transactions');

        $response->assertOk()
            ->assertJsonStructure(['data', 'current_page', 'last_page']);
    }

    public function test_index_filters_by_month(): void
    {
        Transaction::factory()->create(['date' => '2024-06-15']);
        Transaction::factory()->create(['date' => '2024-07-10']);

        $response = $this->getJson('/api/transactions?month=2024-06');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
    }

    public function test_index_filters_by_type(): void
    {
        Transaction::factory()->create(['type' => 'income', 'amount' => 500]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 100]);

        $response = $this->getJson('/api/transactions?type=income');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(500, (float) $response->json('data.0.amount'));
    }

    public function test_store_creates_transaction(): void
    {
        $category = Category::factory()->create(['type' => 'expense']);

        $response = $this->postJson('/api/transactions', [
            'date' => '2024-06-15',
            'amount' => 250.00,
            'type' => 'expense',
            'category_id' => $category->id,
            'note' => 'Test transaction',
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['note' => 'Test transaction']);
        $this->assertDatabaseHas('transactions', ['note' => 'Test transaction']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->postJson('/api/transactions', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['date', 'amount', 'type']);
    }

    public function test_store_validates_amount_greater_than_zero(): void
    {
        $response = $this->postJson('/api/transactions', [
            'date' => '2024-06-15',
            'amount' => 0,
            'type' => 'expense',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['amount']);
    }

    public function test_store_validates_future_date(): void
    {
        $response = $this->postJson('/api/transactions', [
            'date' => '2099-01-01',
            'amount' => 100,
            'type' => 'expense',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['date']);
    }

    public function test_update_modifies_transaction(): void
    {
        $transaction = Transaction::factory()->create(['note' => 'Original']);

        $response = $this->patchJson("/api/transactions/{$transaction->id}", [
            'date' => $transaction->date->format('Y-m-d'),
            'amount' => $transaction->amount,
            'type' => $transaction->type,
            'note' => 'Updated note',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['note' => 'Updated note']);
        $this->assertDatabaseHas('transactions', ['note' => 'Updated note']);
    }

    public function test_destroy_deletes_transaction(): void
    {
        $transaction = Transaction::factory()->create();

        $response = $this->deleteJson("/api/transactions/{$transaction->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }

    public function test_destroy_returns_404_for_nonexistent(): void
    {
        $response = $this->deleteJson('/api/transactions/99999');

        $response->assertNotFound();
    }

    public function test_export_returns_csv(): void
    {
        Transaction::factory()->count(2)->create();

        $response = $this->getJson('/api/transactions?export=1');

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=utf-8')
            ->assertHeader('Content-Disposition', 'attachment; filename=ledger_export_' . date('Y-m') . '.csv');
    }
}
