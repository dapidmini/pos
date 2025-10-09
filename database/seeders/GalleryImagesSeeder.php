<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Services\ImageGeneratorService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
                $kodeBaru = $item->kode_barang ?? 'UNKNOWN';
                $fileName = strtolower($module) . "-{$kodeBaru}.jpg";
                $filePath = "img/{$folderName}/{$fileName}";
                $fullPath = public_path($filePath);

                // pastikan folder tujuan memang ada
                File::ensureDirectoryExists(dirname($fullPath), 0755, true);

                ImageGeneratorService::downloadRandomOrPlaceholder($fullPath, 600);

                GalleryImage::updateOrCreate(
                    [
                        'imageable_id' => $item->id,
                        'imageable_type' => $modelClass,
                    ],
                    [
                        'file_path'     => $filePath,
                        'original_name' => $fileName,
                    ]
                );
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
