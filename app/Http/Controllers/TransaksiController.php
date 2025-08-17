<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransaksiRequest;
use App\Models\DetailTransaksi;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $filterBy = $request->input('filterBy');
        $filterKeyword = $request->input('filterKeyword');

        $query = Transaksi::with(['details.product']);

        // Filter sesuai pilihan
        switch ($filterBy) {
            case 'customer':
                if ($filterKeyword) {
                    $query->where('nama_customer', 'like', "%{$filterKeyword}%");
                }
                break;

            case 'produk':
                if ($filterKeyword) {
                    $query->whereHas('details.product', function ($q) use ($filterKeyword) {
                        $q->where('nama', 'like', "%{$filterKeyword}%");
                    });
                }
                break;

            case 'catatan':
                if ($filterKeyword) {
                    $query->where('keterangan', 'like', "%{$filterKeyword}%");
                }
                break;
        }

        $query->orderBy('created_at', 'desc');

        // Gunakan pagination
        $data = $query->paginate(10)->appends($request->all());

        if (request()->ajax()) {
            return response()->view('transaksi.index-data-container', compact('data'));
        }

        return view('transaksi.index', compact('data'));
    }

    public function create()
    {
        // Ambil semua produk untuk mengisi dropdown di form detail
        $products = Product::all();

        return view('transaksi.create', compact('products'));
    }

    public function edit($id)
    {
        $transaksi = Transaksi::with(['details.product'])->findOrFail($id);
        $products = Product::all();

        return view('transaksi.edit', compact('transaksi', 'products'));
    }

    public function store(TransaksiRequest $request)
    {
        $validatedData = $request->validated();

        $hitungTotal = 0;
        $count = 0;
        foreach ($validatedData['details'] as $detail) {
            $count += $detail['jumlah'];
            $hitungTotal += ($detail['jumlah'] * $detail['harga']);
        }

        $diskonGlobal = $validatedData['diskon'] ?? 0;
        $grandTotal = $hitungTotal - $diskonGlobal;
        if ($grandTotal < 0) {
            $grandTotal = 0;
        }

        $validatedData['total'] = $grandTotal;

        DB::beginTransaction();

        try {
            if ($count < 1) {
                throw new \Exception("Sebuah transaksi harus memiliki minimal 1 item.");
            }
            $user_id = 1; // auth()->id();

            // buat kode invoice
            $tgl_transaksi = Carbon::parse($validatedData['tanggal']);
            $ymd = $tgl_transaksi->format('Ymd'); // format YYYYMMDD
            $transaksi_terakhir = Transaksi::whereDate('tanggal', $tgl_transaksi->toDateString())
                ->lockForUpdate() // mengunci baris untuk memastikan unique
                ->orderBy('id', 'desc')
                ->first();

            $noUrut = 0;
            if ($transaksi_terakhir) {
                $noUrut = (int) substr($transaksi_terakhir->kode_invoice, -5); // '00035' -> 35
            }

            $noUrut++;
            $strNoUrut  = str_pad($noUrut, 5, '0', STR_PAD_LEFT);

            $kodeInvoice = 'INV' . $ymd . $strNoUrut;

            if (Transaksi::where('kode_invoice', $kodeInvoice)->exists()) {
                throw new \Exception("Kode invoice '$kodeInvoice' sudah ada. Silakan coba lagi.");
            }

            $now = now();

            $prepData = [
                'user_id' => $user_id,
                'kode_invoice' => $kodeInvoice,
                'tanggal' => $validatedData['tanggal'],
                'nama_customer' => $validatedData['nama_customer'],
                'meja' => $validatedData['meja'],
                'keterangan' => 'abc',
                'diskon' => $validatedData['diskon'],
                'total' => $validatedData['total'],
                'status' => 'pending',
            ];

            // insert data utama ke tabel Transaksis
            $transaksiUtama = Transaksi::create($prepData);

            // insert multiple data ke tabel detail transaksi
            $insertDetails = [];
            $updateProducts = [];
            foreach ($validatedData['details'] as $key => $detail) {
                $insertDetails[] = [
                    'transaksi_id' => $transaksiUtama->id,
                    'product_id' => $detail['product_id'] ?? null,
                    'jumlah' => $detail['jumlah'] ?? 0,
                    'harga' => $detail['harga'] ?? 0,
                    'subtotal' => $detail['jumlah'] * $detail['harga'],
                    'catatan' => $detail['catatan'] ?? null,
                    'diskon' => $detail['diskon'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // update stok produk
                $product = Product::find($detail['product_id']);
                if ($product) {
                    $newStok = $product->stok - $detail['jumlah'];
                    if ($newStok < 0) {
                        throw new \Exception("Stok produk '{$product->nama}' tidak cukup untuk transaksi ini.");
                    }
                    $updateProducts[] = [
                        'id' => $detail['product_id'],
                        'stok' => $newStok,
                    ];
                }
            }
            // end foreach ($validatedData['details'] as $key => $detail) 

            DetailTransaksi::insert($insertDetails);

            // update stok produk secara massal
            // foreach ($updateProducts as $update) {
            //     Product::where('id', $update['id'])->update(['stok' => $update['stok']]);
            // }
            $ids = array_column($updateProducts, 'id'); // ambil semua id produk
            $caseStatements = []; // untuk menyimpan case statement
            $params = []; // untuk menyimpan parameter binding

            foreach ($updateProducts as $update) {
                // buat case statement untuk update stok
                // ini akan menghasilkan query seperti:
                // UPDATE products SET stok = CASE id WHEN 1 THEN 10 WHEN 2 THEN 5 END WHERE id IN (1, 2)
                // yang akan mengupdate stok produk
                // berdasarkan id produk yang diberikan dan stok baru yang diberikan
                $caseStatements[] = "WHEN id = ? THEN ?";
                $params[] = $update['id']; // tambahkan id produk
                $params[] = $update['stok']; // tambahkan stok baru
            }
            // end foreach ($updateProducts as $update)

            // gabungkan semua case statement menjadi satu string
            $caseString = implode(' ', $caseStatements);

            // update stok produk secara massal
            DB::update("
                UPDATE products 
                SET stok = CASE $caseString
                    ELSE stok
                END 
                WHERE id IN (" . implode(',', $ids) . ")
            ", array_merge($params, $ids));

            DB::commit();

            // kalau pake Form Submit biasa, gunakan ini
            return redirect()->route('transaksis.index')
                ->with('success', "Transaksi '$kodeInvoice' berhasil dibuat.");
        } catch (\Exception $e) {
            DB::rollBack();

            // kalau pake Form Submit biasa, gunakan ini
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);
        $transaksi->load(['details.product']);

        return view('transaksi.show', compact('transaksi'));
    }
}
