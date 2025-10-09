<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Product extends Model
{
    use HasFactory;

    // protected $fillable = ['nama', 'stok', 'harga_beli', 'harga_jual', 'status'];
    protected $guarded = ['id'];

    // untuk autogenerate kode_barang
    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // cari kode_barang dengan angka id_product (6 angka terakhir) paling besar utk id_kategori ini
            $lastKode = Product::where('id_kategori', $product->id_kategori)
                ->whereNotNull('kode_barang')
                ->latest('id')
                ->value('kode_barang');

            // default urutan 1
            $nextNumber = 1;
            // jika ada, ambil angka id_productnya, lalu +1
            if ($lastKode) {
                // ambil angka setelah tanda "-" (menandakan bag.product)
                [$kategoriPart, $productPart] = explode('-', $lastKode);
                // hapus semua karakter non-digit
                $lastNumber = (int) preg_replace('/\D/', '', $productPart);
                $nextNumber = $lastNumber + 1;
            }

            // generate kode unik yg baru
            $kodeBaru = 'K' . str_pad($product->id_kategori, 5, '0', STR_PAD_LEFT)
                . '-'
                . 'I' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            // pastikan kode barang ini unik
            while (Product::where('kode_barang', $kodeBaru)->exists()) {
                $nextNumber++;
                $kodeBaru = 'K' . str_pad($product->id_kategori, 5, '0', STR_PAD_LEFT)
                    . '-'
                    . 'I' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }

            $product->kode_barang = $kodeBaru;
        });
    }

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

    public function galleryImages()
    {
        return $this->morphMany(GalleryImage::class, 'imageable');
    }

    public function getMainImageUrlAttribute()
    {
        $gallery = $this->galleryImages()->first();
        if ($gallery && file_exists(public_path($gallery->file_path))) {
            return asset($gallery->file_path);
        }

        return asset('img/placeholder-no-image.png');
    }
}
