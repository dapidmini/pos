<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ImageGeneratorService
{
    /**
     * Unduh gambar random dari Picsum atau fallback ke placeholder.
     *
     * @param string $destinationPath Path absolut file tujuan (misal: public_path('img/products/img1.jpg'))
     * @param int $width  Lebar gambar random
     * @param int $height Tinggi gambar random
     * @return string Path absolut hasil akhir
     */
    public static function downloadRandomOrPlaceholder(string $destinationPath, int $width = 600, int $height = 400): string
    {
        $dir = dirname($destinationPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        try {
            $imageUrl = "https://picsum.photos/{$width}/{$height}?random=" . rand(1, 9999);
            $imageContent = @file_get_contents($imageUrl);

            if ($imageContent !== false) {
                file_put_contents($destinationPath, $imageContent);
                return $destinationPath;
            }

            // fallback
            $placeholder = public_path('img/placeholder-no-image.jpg');
            copy($placeholder, $destinationPath);
            return $destinationPath;

        } catch (\Throwable $e) {
            Log::warning("Gagal download gambar random: " . $e->getMessage());
            $placeholder = public_path('img/placeholder-no-image.jpg');
            copy($placeholder, $destinationPath);
            return $destinationPath;
        }
    }
}
