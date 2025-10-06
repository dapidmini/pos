<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\GalleryImage;

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

        // simpan ke storage/app/temp (lokal)
        // menggunakan disk default (local) sehingga path akan like "temp/xxxxx.jpg"
        $path = $file->storeAs('public/temp', $fileName);

        // ✅ Pastikan path URL bisa diakses publik
        $publicUrl = asset('storage/temp/' . $fileName);

        // balikan JSON supaya client (Dropzone) tahu nama/path file
        return response()->json([
            'success'       => true,
            'file_path'     => $path,
            'file_name'     => $fileName,
            'original_name' => $originalName,
            'public_url'    => $publicUrl,
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
            $tempPath = 'public/temp/' . $fileName;

            if (!Storage::exists($tempPath)) {
                continue;
            }

            // Folder tujuan berdasarkan tipe model
            $targetFolder = 'public/gallery/' . class_basename($model) . '/' . $model->id;
            $newPath = $targetFolder . '/' . $fileName;

            // Pindahkan file
            Storage::move($tempPath, $newPath);

            // Simpan ke database
            GalleryImage::create([
                'model_id'   => $model->id,
                'model_type' => get_class($model),
                'file_path'  => str_replace('public/', '', $newPath),
                'file_name'  => $fileName,
            ]);
        }
    }
}
