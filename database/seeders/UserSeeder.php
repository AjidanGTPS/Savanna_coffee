<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Owner Savana',
                'email' => 'owner@savana.coffee',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'is_active' => true,
            ],
            [
                'name' => 'Budi Manajer',
                'email' => 'manajer@savana.coffee',
                'password' => Hash::make('password'),
                'role' => 'manajer',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Kasir',
                'email' => 'kasir@savana.coffee',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'is_active' => true,
            ],
            [
                'name' => 'Ahmad Gudang',
                'email' => 'gudang@savana.coffee',
                'password' => Hash::make('password'),
                'role' => 'kepala_gudang',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }
    }
}
