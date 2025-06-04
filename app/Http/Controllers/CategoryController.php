<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category; // Import model

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::all();

        return view('category.index', compact('data'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(CategoryRequest $request, Category $category)
    {
        // Validasi sudah dilakukan oleh CategoryRequest
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
