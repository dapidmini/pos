<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    public function index()
    {
        $data = Supplier::all();

        return view('supplier.index', compact('data'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function store(SupplierRequest $request)
    {
        Supplier::create($request->validated());

        return redirect()->route('suppliers.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        // Validasi sudah dilakukan oleh SupplierRequest
        $supplier->update($request->validated());

        return redirect()->route('suppliers.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
