<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'amount' => fake()->randomFloat(2, 10, 5000),
            'type' => fake()->randomElement(['income', 'expense']),
            'category_id' => Category::factory(),
            'note' => fake()->sentence(3),
        ];
    }
}
