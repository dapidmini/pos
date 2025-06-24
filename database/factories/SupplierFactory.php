<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->company, // Menghasilkan nama perusahaan/organisasi acak
            'telepon' => $this->faker->phoneNumber, // Menghasilkan nomor telepon acak
            'alamat' => $this->faker->address, // Menghasilkan alamat acak
            'email' => $this->faker->unique()->safeEmail, // Menghasilkan email unik dan aman
        ];
    }
}
