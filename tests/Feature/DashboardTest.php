<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_dashboard_structure(): void
    {
        $response = $this->getJson('/api/dashboard');

        $response->assertOk()
            ->assertJsonStructure([
                'balance',
                'income_total',
                'expense_total',
                'daily_series',
                'category_series',
                'vs_previous',
            ]);
    }

    public function test_index_calculates_balance_correctly(): void
    {
        $today = now()->format('Y-m-d');
        Transaction::factory()->create(['type' => 'income', 'amount' => 1000, 'date' => $today]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 300, 'date' => $today]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 200, 'date' => $today]);

        $response = $this->getJson('/api/dashboard');

        $this->assertEquals(1000, (float) $response->json('income_total'));
        $this->assertEquals(500, (float) $response->json('expense_total'));
        $this->assertEquals(500, (float) $response->json('balance'));
    }

    public function test_index_filters_by_month(): void
    {
        Transaction::factory()->create(['type' => 'income', 'amount' => 500, 'date' => '2024-06-15']);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 100, 'date' => '2024-07-10']);

        $response = $this->getJson('/api/dashboard?month=2024-06');

        $this->assertEquals(500, (float) $response->json('income_total'));
        $this->assertEquals(0, (float) $response->json('expense_total'));
    }

    public function test_index_returns_daily_series(): void
    {
        Transaction::factory()->create(['type' => 'income', 'amount' => 100, 'date' => now()->format('Y-m-d')]);

        $response = $this->getJson('/api/dashboard');

        $this->assertNotEmpty($response->json('daily_series'));
    }

    public function test_index_returns_category_series(): void
    {
        $today = now()->format('Y-m-d');
        $category = Category::factory()->create(['type' => 'expense', 'color_hex' => '#FF3B30', 'icon' => 'car']);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 150, 'category_id' => $category->id, 'date' => $today]);

        $response = $this->getJson('/api/dashboard');

        $this->assertCount(1, $response->json('category_series'));
        $this->assertEquals(150, (float) $response->json('category_series.0.total'));
        $this->assertEquals($category->name, $response->json('category_series.0.name'));
    }

    public function test_index_returns_vs_previous(): void
    {
        Transaction::factory()->create(['type' => 'income', 'amount' => 500, 'date' => now()->subMonth()->format('Y-m-d')]);

        $response = $this->getJson('/api/dashboard');

        $this->assertIsNumeric($response->json('vs_previous'));
    }

    public function test_index_returns_zero_when_no_transactions(): void
    {
        $response = $this->getJson('/api/dashboard');

        $this->assertEquals(0, (float) $response->json('balance'));
        $this->assertEquals(0, (float) $response->json('income_total'));
        $this->assertEquals(0, (float) $response->json('expense_total'));
    }
}
