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
        // Validasi basic: wajib image, hanya jpg/jpeg/png, max 5MB
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();

        // nama file aman dan unik
        $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        // simpan ke storage/app/temp (lokal)
        // menggunakan disk default (local) sehingga path akan like "temp/xxxxx.jpg"
        $path = $file->storeAs('temp', $fileName);

        // balikan JSON supaya client (Dropzone) tahu nama/path file
        return response()->json([
            'file_path'     => $path,
            'file_name'     => $fileName,
            'original_name' => $originalName,
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
}
