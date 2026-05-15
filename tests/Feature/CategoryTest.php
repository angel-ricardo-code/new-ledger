<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response->assertOk();
        $this->assertCount(3, $response->json());
    }

    public function test_index_ordered_by_name(): void
    {
        Category::factory()->create(['name' => 'Zoo']);
        Category::factory()->create(['name' => 'Alpha']);

        $response = $this->getJson('/api/categories');

        $names = collect($response->json())->pluck('name')->toArray();
        $this->assertEquals(['Alpha', 'Zoo'], $names);
    }

    public function test_store_creates_category(): void
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'Transporte',
            'type' => 'expense',
            'color_hex' => '#FF3B30',
            'icon' => 'car',
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'Transporte']);
        $this->assertDatabaseHas('categories', ['name' => 'Transporte']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->postJson('/api/categories', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'type', 'color_hex']);
    }

    public function test_store_allows_duplicate_name(): void
    {
        Category::factory()->create(['name' => 'Duplicate']);

        $response = $this->postJson('/api/categories', [
            'name' => 'Duplicate',
            'type' => 'expense',
            'color_hex' => '#FF3B30',
        ]);

        $response->assertCreated();
    }

    public function test_store_validates_color_format(): void
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'Test',
            'type' => 'expense',
            'color_hex' => 'invalid',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['color_hex']);
    }

    public function test_update_modifies_category(): void
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->patchJson("/api/categories/{$category->id}", [
            'name' => 'New Name',
            'type' => $category->type,
            'color_hex' => '#FF9500',
            'icon' => 'zap',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'New Name']);
        $this->assertDatabaseHas('categories', ['name' => 'New Name']);
        $this->assertDatabaseMissing('categories', ['name' => 'Old Name']);
    }

    public function test_update_allows_same_name(): void
    {
        $category = Category::factory()->create(['name' => 'Unique']);

        $response = $this->patchJson("/api/categories/{$category->id}", [
            'name' => 'Unique',
            'type' => $category->type,
            'color_hex' => '#FF9500',
        ]);

        $response->assertOk();
    }

    public function test_destroy_deletes_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_destroy_sets_null_on_related_transactions(): void
    {
        $category = Category::factory()->create();
        $transaction = Transaction::factory()->create(['category_id' => $category->id]);

        $this->deleteJson("/api/categories/{$category->id}");

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'category_id' => null,
        ]);
    }
}
