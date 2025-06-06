<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiRequest;
use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $data = Transaksi::with('user')->latest()->paginate(10);

        return view('transaksi.index', compact('data'));
    }

    public function create()
    {
        // Ambil semua produk untuk mengisi dropdown di form detail
        $products = Product::all();

        return view('transaksi.create', compact('products'));
    }

    public function store(Request $request)
    {
        dd($request->input());
    }
}
