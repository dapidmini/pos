<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    //
    protected $table = 'detail_transaksis';

    protected $guarded = ['id'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            if ($detail->jumlah !== null && $detail->harga !== null) {
                $detail->subtotal = $detail->jumlah * $detail->harga;
            }
        });
    }
}
