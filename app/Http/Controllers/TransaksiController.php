<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $data = Transaksi::with('user')->latest()->paginate(10);

        return view('transaksi.index', compact('data'));
    }
}
