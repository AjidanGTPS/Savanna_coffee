<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Table::firstOrCreate(
                ['number' => $i],
                [
                    'name' => "Meja $i",
                    'barcode_token' => Str::random(32),
                    'status' => 'available',
                    'capacity' => 4,
                    'is_active' => true,
                ]
            );
        }
    }
}
