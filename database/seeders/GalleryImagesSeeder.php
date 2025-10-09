<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GalleryImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($module = 'Product')
    {
        try {
            DB::beginTransaction();

            // Pastikan nama class dan folder sesuai konvensi
            $modelClass = "App\\Models\\{$module}";
            $folderName = strtolower($module) . 's'; // misal: Product -> products

            if (!class_exists($modelClass)) {
                $this->command->error("Model {$modelClass} tidak ditemukan.");
                return;
            }

            $items = $modelClass::whereDoesntHave('galleryImages')->get();

            if ($items->isEmpty()) {
                $this->command->info("Semua {$folderName} sudah memiliki gallery image.");
                DB::rollBack();
                return;
            }

            foreach ($items as $item) {
                $kodeBaru = 'UNKNOWN';
                $fileName = $filePath = '';
                if ($module == 'Product' && $item->kode_barang) {
                    $kodeBaru = $item->kode_barang;
                    $fileName = "product-{$kodeBaru}.jpg";
                }
                $filePath = "img/{$folderName}/{$fileName}";

                // pastikan folder tujuan memang ada
                $destinationPath = public_path("img/{$folderName}");
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }

                // cek apakah file fisik ada, jika tidak maka copy placeholder
                $absoluteFilePath = public_path($filePath);
                if (!File::exists($absoluteFilePath)) {
                    $placeholder = public_path('img/placeholder-no-image.jpg');
                    if (File::exists($placeholder)) {
                        File::copy($placeholder, $absoluteFilePath);
                    } else {
                        $this->command->warn("⚠️ File placeholder tidak ditemukan di {$placeholder}");
                    }
                }
                
                // Simpan data gallery
                \App\Models\GalleryImage::create([
                    'imageable_id'   => $item->id,
                    'imageable_type' => $modelClass,
                    'file_path'      => $filePath,
                    'original_name'  => $fileName,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            DB::commit();
            $this->command->info("Seeder GalleryImagesSeeder selesai: " . count($items) . " data {$folderName} ditambahkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Seeder GalleryImagesSeeder gagal: " . $e->getMessage());
            $this->command->error("Seeder gagal: " . $e->getMessage());
        }
    }
}
