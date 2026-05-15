<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Salario', 'type' => 'income', 'color_hex' => '#34C759', 'icon' => 'briefcase'],
            ['name' => 'Freelance', 'type' => 'income', 'color_hex' => '#30B0C7', 'icon' => 'laptop'],
            ['name' => 'Inversiones', 'type' => 'income', 'color_hex' => '#5856D6', 'icon' => 'trending-up'],
            ['name' => 'Alimentación', 'type' => 'expense', 'color_hex' => '#FF9500', 'icon' => 'utensils'],
            ['name' => 'Transporte', 'type' => 'expense', 'color_hex' => '#FF3B30', 'icon' => 'car'],
            ['name' => 'Servicios', 'type' => 'expense', 'color_hex' => '#AF52DE', 'icon' => 'zap'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                [
                    ...$category,
                    'user_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
