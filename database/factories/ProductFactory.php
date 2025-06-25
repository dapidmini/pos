<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $satuan = ['pcs', 'kg', 'liter', 'box', 'pack'];

        $hargaBeli = $this->faker->numberBetween(10, 100) * 1000; // Menghasilkan harga beli dalam rentang yang lebih besar
        $profit = $this->faker->numberBetween(5, 10) * 1000; // Menghasilkan profit dalam rentang yang lebih besar
        $hargaJual = $hargaBeli + $profit; // Menghitung harga jual

        // Gabungan semua jenis benda dari kategori yang Anda berikan
        // Ini adalah array yang akan menjadi sumber utama nama produk
        $jenisBenda = [
            // Makanan & Minuman
            'Mie Instan',
            'Keripik Kentang',
            'Cokelat Batangan',
            'Kopi Bubuk',
            'Gula Pasir',
            'Air Mineral Botol',
            'Susu UHT',
            'Jus Buah Kotak',
            'Teh Celup',
            'Minuman Isotonik',

            // Elektronik
            'Smart TV',
            'Smartphone Android',
            'Laptop Gaming',
            'Power Bank',
            'Earphone Bluetooth',
            'Smartwatch',
            'Kipas Angin Portable',
            'Setrika Listrik',
            'Blender Juicer',
            'Rice Cooker Digital',

            // Pakaian
            'Kaos Katun Pria',
            'Kemeja Batik Wanita',
            'Celana Jeans Slim Fit',
            'Jaket Parasut',
            'Gamis Modern',
            'Sepatu Sneakers',
            'Sandal Jepit',
            'Topi Baseball',
            'Jilbab Segiempat',
            'Daster Batik',

            // Peralatan Rumah Tangga
            'Sapu Ijuk',
            'Pembersih Lantai',
            'Wajan Anti Lengket',
            'Piring Keramik',
            'Gelas Kaca',
            'Sendok Garpu Set',
            'Keset Kaki',
            'Lap Microfiber',
            'Ember Plastik',
            'Gantungan Baju',

            // Kecantikan
            'Sabun Cuci Muka',
            'Pelembab Wajah',
            'Serum Anti-Aging',
            'Lipstik Matte',
            'Maskara Waterproof',
            'Shampo Rambut',
            'Kondisioner',
            'Handbody Lotion',
            'Parfum Eau De Toilette',
            'Krim Mata',

            // Olahraga
            'Bola Sepak Kulit',
            'Raket Badminton',
            'Shuttlecock',
            'Matras Yoga',
            'Dumbell Set',
            'Tali Skipping',
            'Pakaian Renang',
            'Kacamata Renang',
            'Sepatu Lari',
            'Handuk Olahraga',

            // Buku
            'Novel Fiksi Fantasi',
            'Buku Resep Masakan',
            'Ensiklopedia Anak',
            'Komik Manga',
            'Buku Motivasi Bisnis',
            'Buku Pelajaran Sejarah',
            'Kamus Bahasa Inggris',
            'Buku Mewarnai Dewasa',
            'Atlas Dunia',
            'Memoar Tokoh',

            // Tambahan generik jika diperlukan
            'Alat Tulis Set',
            'Perkakas Tangan',
            'Perlengkapan Mandi Travel',
            'Hiasan Dinding Unik'
        ];

        // Deskripsi tambahan atau material
        $deskripsiTambahan = [
            'Mini',
            'Pro',
            'Smart',
            'Elektrik',
            'Ergonomis',
            'Portable',
            'Anti Karat',
            'Kayu',
            'Plastik',
            'Besi',
            'Kaca',
            'Keramik',
            'Stainless Steel',
            'Silikon',
            'Dinding',
            'Meja',
            'Lantai',
            'Gantung',
            'Lipat',
            'Ukuran Besar',
            'Praktis',
            'Premium',
            'Reguler',
            'Jumbo',
            'Original',
            'Varian Baru',
            'Edisi Terbatas'
        ];

        // Warna (opsional, jika relevan)
        $warna = [
            'Merah',
            'Biru',
            'Hijau',
            'Hitam',
            'Putih',
            'Abu-abu',
            'Coklat',
            'Transparan',
            'Kuning',
            'Pink'
        ];

        // --- Proses generate nama_produk ---
        $namaProduk = $this->faker->randomElement($jenisBenda);
        // Tambahkan deskripsi atau material (opsional, bisa ada atau tidak)
        if ($this->faker->boolean(70)) { // 70% kemungkinan ditambahkan
            $namaProduk .= ' ' . $this->faker->randomElement($deskripsiTambahan);
        }

        // Tambahkan warna (opsional, bisa ada atau tidak)
        if ($this->faker->boolean(50)) { // 50% kemungkinan ditambahkan
            $namaProduk .= ' ' . $this->faker->randomElement($warna);
        }

        return [
            'nama' => $namaProduk, // Menghasilkan nama perusahaan/organisasi acak
            'stok' => $this->faker->numberBetween(3, 10) * 10, // Menghasilkan nomor telepon acak
            'satuan' => $this->faker->randomElement($satuan), // Menghasilkan alamat acak
            'harga_beli' => $hargaBeli, // Menghasilkan harga beli acak
            'harga_jual' => $hargaJual, // Menghasilkan harga jual acak
            'status' => 1, // Status produk (1 untuk aktif, 0 untuk tidak aktif)
            'id_kategori' => $this->faker->numberBetween(1, 5), // Menghasilkan ID kategori acak antara 1 dan 5
            'id_supplier' => $this->faker->numberBetween(1, 10), // Menghasilkan ID supplier ac
        ];
    }
}
