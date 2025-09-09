<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    // Runs the database seeds
    public function run(): void
    {
        User::create([
            'name' => 'Fathan Muhamad Raffi',
            'email' => 'fathan@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
