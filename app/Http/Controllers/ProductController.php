<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
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
        Product::create($request->validated());

        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus!');
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
