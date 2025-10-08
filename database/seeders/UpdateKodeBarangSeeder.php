<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UpdateKodeBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::where(function($q) {
                    $q->whereNull('kode_barang')
                    ->orWhere('kode_barang', '');
                })
                ->orderBy('id_kategori')
                ->orderBy('id')
                ->get();

        $counterPerKategori = [];

        DB::beginTransaction();

        foreach ($products as $product) {
            $idKategori = $product->id_kategori;

            // siapkan counter per kategori
            if (!isset($counterPerKategori[$idKategori])) {
                $counterPerKategori[$idKategori] = 1;

                // cari angka urutan terbesar untuk kategori ini
                $lastKode = Product::where('id_kategori', $idKategori)
                    ->whereNotNull('kode_barang')
                    ->where('kode_barang', '!=', '')
                    ->latest('id')
                    ->value('kode_barang');

                $lastNumber = 0;
                if ($lastKode) {
                    [, $productPart] = explode('-', $lastKode);
                    $lastNumber = (int) preg_replace('/\D/', '', $productPart);
                }
                $counterPerKategori[$idKategori] = $lastNumber;
            }

            // increment counter kategori ini
            $counterPerKategori[$idKategori]++;

            // generate kode baru
            $kodeBaru = 'K' . str_pad($idKategori, 4, '0', STR_PAD_LEFT) .
                        '-' .
                        'I' . str_pad($counterPerKategori[$idKategori], 5, '0', STR_PAD_LEFT);

            // update data produk
            $product->kode_barang = $kodeBaru;
            $product->save();

            // Cek apakah gallery image untuk produk ini sudah ada
            $existing = GalleryImage::where('imageable_id', $product->id)
                ->where('imageable_type', Product::class)
                ->exists();

            if (!$existing) {
                // Buat record baru untuk gallery_images
                GalleryImage::create([
                    'imageable_id'   => $product->id,
                    'imageable_type' => Product::class,
                    'file_path'      => 'img/placeholder-no-image.jpg',
                    'original_name'  => 'placeholder-' . Str::random(6) . '.jpg',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }
    }
}
