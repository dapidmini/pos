<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat satu user admin spesifik (misalnya untuk login awal)
        User::create([
            'name' => 'Admin Utama',
            'username' => 'admin', // Username spesifik
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Password 'password'
            'email_verified_at' => now(),
        ]);
    }
}
