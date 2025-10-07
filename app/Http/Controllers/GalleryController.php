<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Upload file sementara (dipanggil oleh Dropzone).
     */
    public function uploadTemp(Request $request)
    {
        // ✅ Pastikan file dikirim (Dropzone selalu kirim sebagai "file")
        if (!$request->hasFile('file')) {
            return response()->json([
                'error' => 'Tidak ada file yang diupload.'
            ], 400);
        }

        // Validasi basic: wajib image, hanya jpg/jpeg/png, max 5MB
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $originalExt = $file->getClientOriginalExtension();

        // nama file aman dan unik
        $fileName = time() . '_' . Str::random(8) . '.' . $originalExt;

        $tempDir = 'img/temp';

        $destinationPath = public_path($tempDir);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $file->move($destinationPath, $fileName);

        // ✅ Pastikan path URL bisa diakses publik
        $publicUrl = asset($tempDir . '/' . $fileName);

        // balikan JSON supaya client (Dropzone) tahu nama/path file
        return response()->json([
            'success'       => true,
            'file_name'     => $fileName,
            'file_path'     => $tempDir . '/' . $fileName,
            'original_name' => $originalName,
            'public_url'    => asset($publicUrl),
        ]);
    }

    /**
     * (Optional) Hapus file sementara lewat AJAX.
     */
    public function deleteTemp(Request $request)
    {
        $request->validate([
            'file' => 'required|string',
        ]);

        $file = basename($request->input('file'));
        $path = 'temp/' . $file;

        if (Storage::exists($path)) {
            Storage::delete($path);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

    /**
     * Pindahkan file dari temp ke folder model (Product/Supplier)
     */
    public static function moveFromTempToGallery(array $tempFiles, $model)
    {
        foreach ($tempFiles as $fileName) {
            try {
                $sourcePath = public_path('img/temp/' . $fileName);
                if (!file_exists($sourcePath)) {
                    continue;
                }

                // Folder tujuan: public/img/gallery/Product/1/
                $modelName = Str::studly(class_basename($model));
                $direktori = "img/gallery/{$modelName}/{$model->id}";
                $targetDir = public_path($direktori);
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $targetPath = $targetDir . '/' . $fileName;

                // Pindahkan file
                rename($sourcePath, $targetPath);

                // Simpan di database
                \App\Models\GalleryImage::create([
                    'model_id'   => $model->id,
                    'model_type' => get_class($model),
                    'file_path'  => $direktori . '/' . $fileName,
                    'file_name'  => $fileName,
                ]);
            } catch (\Throwable $e) {
                // Handle error
                Log::error("Failed to move file {$fileName} to gallery: " . $e->getMessage());
            }
        }
    }
}
