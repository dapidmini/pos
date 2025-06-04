<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    //
    protected $fillable = ['id'];

    public function products()
    {
        return $this->hasMany(Product::class, 'id_satuan');
    }
}
