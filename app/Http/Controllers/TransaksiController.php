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
            }

            DetailTransaksi::insert($insertDetails);

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
}
