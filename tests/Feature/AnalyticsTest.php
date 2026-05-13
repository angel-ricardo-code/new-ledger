<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_overview_returns_monthly_data(): void
    {
        $today = now()->format('Y-m-d');
        Transaction::factory()->create(['type' => 'income', 'amount' => 1000, 'date' => $today]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 300, 'date' => $today]);

        $response = $this->getJson('/api/analytics/overview?months=1');

        $response->assertOk();
        $this->assertCount(1, $response->json());
        $this->assertEquals(1000, (float) $response->json('0.income'));
        $this->assertEquals(300, (float) $response->json('0.expense'));
    }

    public function test_overview_defaults_to_six_months(): void
    {
        $response = $this->getJson('/api/analytics/overview');

        $response->assertOk();
        $this->assertCount(6, $response->json());
    }

    public function test_overview_returns_structured_data(): void
    {
        $response = $this->getJson('/api/analytics/overview?months=1');

        $response->assertOk()
            ->assertJsonStructure([['month', 'label', 'income', 'expense', 'reconciliation', 'balance']]);
    }

    public function test_top_transactions_returns_top_income_and_expense(): void
    {
        $today = now()->format('Y-m-d');
        $cat = Category::factory()->create(['name' => 'Food', 'color_hex' => '#FF9500', 'icon' => 'utensils']);
        Transaction::factory()->create(['type' => 'income', 'amount' => 500, 'date' => $today]);
        Transaction::factory()->create(['type' => 'income', 'amount' => 100, 'date' => $today]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 200, 'date' => $today, 'category_id' => $cat->id]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 50, 'date' => $today]);

        $response = $this->getJson('/api/analytics/top-transactions?limit=3');

        $response->assertOk();
        $this->assertCount(2, $response->json('top_income'));
        $this->assertCount(2, $response->json('top_expense'));
        $this->assertEquals('Food', $response->json('top_expense.0.category.name'));
    }

    public function test_top_transactions_respects_month_filter(): void
    {
        Transaction::factory()->create(['type' => 'expense', 'amount' => 100, 'date' => '2024-06-15']);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 200, 'date' => '2024-07-10']);

        $response = $this->getJson('/api/analytics/top-transactions?month=2024-06');

        $response->assertOk();
        $this->assertCount(1, $response->json('top_expense'));
        $this->assertEquals(100, (float) $response->json('top_expense.0.amount'));
    }

    public function test_weekday_returns_seven_days(): void
    {
        $today = now()->format('Y-m-d');
        Transaction::factory()->create(['type' => 'expense', 'amount' => 100, 'date' => $today]);

        $response = $this->getJson('/api/analytics/weekday');

        $response->assertOk();
        $this->assertCount(7, $response->json());
        $this->assertArrayHasKey('label', $response->json('0'));
        $this->assertArrayHasKey('total', $response->json('0'));
        $this->assertArrayHasKey('count', $response->json('0'));
    }

    public function test_weekday_aggregates_expenses_correctly(): void
    {
        $today = now()->format('Y-m-d');
        $dow = (int) now()->format('w');
        Transaction::factory()->count(3)->create(['type' => 'expense', 'amount' => 50, 'date' => $today]);

        $response = $this->getJson('/api/analytics/weekday');

        $response->assertOk();
        $this->assertEquals(150, (float) $response->json("{$dow}.total"));
        $this->assertEquals(3, $response->json("{$dow}.count"));
    }

    public function test_weekday_ignores_income(): void
    {
        $today = now()->format('Y-m-d');
        Transaction::factory()->create(['type' => 'income', 'amount' => 1000, 'date' => $today]);

        $response = $this->getJson('/api/analytics/weekday');

        $totalSum = collect($response->json())->sum('total');
        $this->assertEquals(0, $totalSum);
    }

    public function test_dashboard_returns_kpi_data(): void
    {
        $today = now()->format('Y-m-d');
        $cat = Category::factory()->create(['name' => 'Transporte', 'color_hex' => '#FF3B30']);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 300, 'date' => $today, 'category_id' => $cat->id]);
        Transaction::factory()->create(['type' => 'expense', 'amount' => 100, 'date' => $today]);
        Transaction::factory()->create(['type' => 'income', 'amount' => 500, 'date' => $today]);

        $response = $this->getJson('/api/dashboard');

        $response->assertOk();
        $kpi = $response->json('kpi');
        $this->assertNotNull($kpi);
        $this->assertArrayHasKey('avg_daily_expense', $kpi);
        $this->assertArrayHasKey('top_expense_category', $kpi);
        $this->assertArrayHasKey('biggest_spending_day', $kpi);
        $this->assertArrayHasKey('biggest_transaction', $kpi);
        $this->assertArrayHasKey('days_without_expenses', $kpi);
        $this->assertEquals('Transporte', $kpi['top_expense_category']['name']);
        $this->assertEquals(300, $kpi['biggest_transaction']['amount']);
    }

    public function test_dashboard_kpi_returns_nulls_when_no_data(): void
    {
        $response = $this->getJson('/api/dashboard');

        $response->assertOk();
        $kpi = $response->json('kpi');
        $this->assertNull($kpi['top_expense_category']);
        $this->assertNull($kpi['biggest_spending_day']);
        $this->assertNull($kpi['biggest_transaction']);
    }
}
