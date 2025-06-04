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
}
