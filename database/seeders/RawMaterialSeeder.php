<?php

namespace Database\Seeders;

use App\Models\RawMaterial;
use Illuminate\Database\Seeder;

class RawMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['name' => 'Biji Kopi Arabika', 'unit' => 'kg', 'current_stock' => 10, 'minimum_stock' => 3, 'category' => 'Kopi'],
            ['name' => 'Biji Kopi Robusta', 'unit' => 'kg', 'current_stock' => 8, 'minimum_stock' => 2, 'category' => 'Kopi'],
            ['name' => 'Susu Full Cream', 'unit' => 'liter', 'current_stock' => 15, 'minimum_stock' => 5, 'category' => 'Susu'],
            ['name' => 'Oat Milk', 'unit' => 'liter', 'current_stock' => 5, 'minimum_stock' => 2, 'category' => 'Susu'],
            ['name' => 'Almond Milk', 'unit' => 'liter', 'current_stock' => 3, 'minimum_stock' => 1, 'category' => 'Susu'],
            ['name' => 'Gula Pasir', 'unit' => 'kg', 'current_stock' => 10, 'minimum_stock' => 3, 'category' => 'Bahan Dasar'],
            ['name' => 'Gula Syrup', 'unit' => 'liter', 'current_stock' => 4, 'minimum_stock' => 1, 'category' => 'Bahan Dasar'],
            ['name' => 'Coklat Bubuk', 'unit' => 'kg', 'current_stock' => 2, 'minimum_stock' => 0.5, 'category' => 'Bahan Dasar'],
            ['name' => 'Caramel Syrup', 'unit' => 'liter', 'current_stock' => 2, 'minimum_stock' => 0.5, 'category' => 'Syrup'],
            ['name' => 'Vanilla Syrup', 'unit' => 'liter', 'current_stock' => 2, 'minimum_stock' => 0.5, 'category' => 'Syrup'],
            ['name' => 'Hazelnut Syrup', 'unit' => 'liter', 'current_stock' => 1.5, 'minimum_stock' => 0.5, 'category' => 'Syrup'],
            ['name' => 'Teh English Breakfast', 'unit' => 'gram', 'current_stock' => 500, 'minimum_stock' => 100, 'category' => 'Teh'],
            ['name' => 'Teh Hijau', 'unit' => 'gram', 'current_stock' => 300, 'minimum_stock' => 100, 'category' => 'Teh'],
            ['name' => 'Chamomile', 'unit' => 'gram', 'current_stock' => 200, 'minimum_stock' => 50, 'category' => 'Teh'],
            ['name' => 'Croissant Frozen', 'unit' => 'pcs', 'current_stock' => 30, 'minimum_stock' => 10, 'category' => 'Bakery'],
            ['name' => 'Donut Frozen', 'unit' => 'pcs', 'current_stock' => 20, 'minimum_stock' => 8, 'category' => 'Bakery'],
            ['name' => 'Roti Sandwich', 'unit' => 'pcs', 'current_stock' => 25, 'minimum_stock' => 8, 'category' => 'Bakery'],
            ['name' => 'Whipped Cream', 'unit' => 'kaleng', 'current_stock' => 5, 'minimum_stock' => 2, 'category' => 'Topping'],
            ['name' => 'Es Batu', 'unit' => 'kg', 'current_stock' => 20, 'minimum_stock' => 5, 'category' => 'Bahan Dasar'],
            ['name' => 'Air Mineral', 'unit' => 'galon', 'current_stock' => 3, 'minimum_stock' => 1, 'category' => 'Bahan Dasar'],
        ];

        foreach ($materials as $material) {
            RawMaterial::firstOrCreate(
                ['name' => $material['name']],
                array_merge($material, ['is_active' => true])
            );
        }
    }
}
