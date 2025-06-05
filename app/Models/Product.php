<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // protected $fillable = ['nama', 'stok', 'harga_beli', 'harga_jual', 'status'];
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function detTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'product_id');
    }

    public function setSatuanAttribute(string $value): void
    {
        // Mengubah nilai menjadi huruf kapital
        $this->attributes['satuan'] = Str::upper(trim($value));
    }
}
