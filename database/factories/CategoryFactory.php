<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'type' => fake()->randomElement(['income', 'expense']),
            'color_hex' => fake()->randomElement(['#FF3B30', '#FF9500', '#34C759', '#0A84FF', '#AF52DE']),
            'icon' => fake()->randomElement(['utensils', 'car', 'zap', 'heart', 'shopping-bag']),
        ];
    }
}
