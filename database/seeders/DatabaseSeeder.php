<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Utama',
            'username' => 'admin', // Username spesifik
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Password 'password'
            'email_verified_at' => now(),
        ]);
        User::factory(10)->create();

        $categories = [
            'Makanan',
            'Minuman',
            'Elektronik',
            'Pakaian',
            'Peralatan Rumah Tangga',
            'Kecantikan',
            'Olahraga',
            'Buku',
        ];
        foreach ($categories as $categoryName) {
            Category::factory()->create([
                'nama' => $categoryName,
            ]);
        }

        Supplier::factory()->count(10)->create();
        Product::factory()->count(30)->create();
    }
}
