<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@fastmovie.nl',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Regular users
        User::create([
            'name' => 'Jan de Vries',
            'email' => 'jan@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Maria Jansen',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Peter Bakker',
            'email' => 'peter@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
    }
}
