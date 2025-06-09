<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Admin Utama',
        //     'username' => 'admin', // Username spesifik
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('password'), // Password 'password'
        //     'email_verified_at' => now(),
        // ]);
    }
}
