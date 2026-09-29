<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Coffee', 'slug' => 'coffee', 'icon' => '☕', 'sort_order' => 1],
            ['name' => 'Makanan', 'slug' => 'makanan', 'icon' => '🍽️', 'sort_order' => 2],
            ['name' => 'Dessert & Minuman', 'slug' => 'dessert-minuman', 'icon' => '🍰', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], array_merge($category, ['is_active' => true]));
        }
    }
}
