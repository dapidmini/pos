<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // protected $fillable = ['nama', 'stok', 'harga_beli', 'harga_jual', 'status'];
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }
}
