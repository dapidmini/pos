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
        $requestData = $request->all();
        $detailsData = [];

        if (isset($requestData['product_id']) && is_array($requestData['product_id'])) {
            $numDetails = count($requestData['product_id']);

            for ($i=0; $i < $numDetails; $i++) { 
                // pastikan setiap elemen array yg akan dipakai bisa diakses
                $productId = $requestData['product_id'][$i] ?? null;
                $jumlah = $requestData['jumlah'][$i] ?? null;
                $harga = $requestData['harga'][$i] ?? null;
                $catatan = $requestData['catatan'][$i] ?? null;
                $diskonItem = $requestData['diskon'][$i] ?? null;

                $calculatedSubtotal = 0;
                if (is_numeric($jumlah) && is_numeric($harga)) {
                    $calculatedSubtotal = ($jumlah * $harga) - $diskonItem;
                }

                $detailsData[] = [
                    'product_id' => (int)$productId,
                    'jumlah' => (int)$jumlah,
                    'harga' => (int)$harga,
                    'subtotal' => (int)$calculatedSubtotal,
                    'catatan' => $catatan,
                    'diskon' => $diskonItem,
                ];
            }
            // end for
        }
        // end if (isset($requestData['product_id']) && is_array($requestData['product_id'])) 

        $request->merge(['details' => $detailsData]);
        $request->merge(['user_id' => 1]);

        try {
            // validasi variabel data
            $rules = [
                // 'user_id' => 'required|integer|exists:users,id',
                'tanggal' => 'required|date',
                'nama_customer' => 'required|string|max:255',
                'meja' => 'required|string|max:20',
                'diskon' => 'nullable|numeric|min:0',
                'total' => 'required|numeric|min:0',
            ];
            $validatedData = $request->validate($rules);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
