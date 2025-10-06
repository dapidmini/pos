<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::all();

        return view('product.index', compact('data'));
    }

    public function create()
    {
        $categories = Category::pluck('nama', 'id');
        $suppliers = Supplier::all();

        return view('product.create', compact('categories', 'suppliers'));
    }

    public function edit(Product $product)
    {
        $categories = Category::pluck('nama', 'id');
        $suppliers = Supplier::all();

        return view('product.edit', compact('product', 'categories', 'suppliers'));
    }

    public function show(Product $product)
    {
        $product = Product::with(['category', 'supplier'])->find($product->id);
        // return response()->json($product);
        return view('product.show', compact('product'));
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'id_kategori' => 'required|exists:categories,id',
            'id_supplier' => 'nullable|exists:suppliers,id',
            'temp_files' => 'nullable|array', // array of temp file names
        ]);

        $product = Product::create($validated);

        // ✅ Pindahkan file temp ke gallery final
        if (!empty($validated['temp_files'])) {
            GalleryController::moveFromTempToGallery($validated['temp_files'], $product);
        }

        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(ProductRequest $request, Product $product)
    {
        // Update data produk utama
        $product->update($request->validated());

        // Proses file yang diupload via Dropzone (jika ada)
        if ($request->filled('tempFilesProductGallery')) {
            foreach ($request->tempFilesProductGallery as $tempFile) {
                $tempPath = storage_path("app/temp/{$tempFile}");

                if (file_exists($tempPath)) {
                    $destinationPath = public_path("uploads/products/{$product->id}");

                    // buat folder jika belum ada
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    // nama file final
                    $finalFilePath = $destinationPath . '/' . $tempFile;
                    // pindahkan file dari temp ke folder permanen
                    rename($tempPath, $finalFilePath);

                    // Simpan ke tabel product_images (opsional, lebih rapi)
                    \App\Models\GalleryImage::create([
                        'imageable_type' => Product::class,
                        'imageable_id' => $product->id,
                        'file_path' => "uploads/products/{$product->id}/{$tempFile}",
                        'original_name' => $tempFile,
                    ]);
                }
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus!');
    }

    public function uploadTemp(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // buat nama unik
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // simpan dulu ke folder sementara
            $file->move(storage_path('app/temp'), $filename);

            return response()->json([
                'file_name' => $filename,
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function uploadFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/products', $filename, 'public');

            // If there's an existing image, you might want to delete it here

            $product->image = '/storage/' . $filePath;
            $product->save();

            return redirect()->route('products.show', $product->id)->with('success', 'File uploaded successfully.');
        }

        return redirect()->route('products.show', $product->id)->with('error', 'No file selected.');
    }
}
