<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Fathan Muhamad Raffi',
            'email' => 'fathan@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
