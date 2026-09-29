<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VarianProduk extends Model
{
    protected $table = 'varian_produk';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['produk_id', 'nama', 'harga', 'urutan', 'tersedia'];

    protected $casts = ['tersedia' => 'boolean'];

    public function produk() { return $this->belongsTo(Produk::class, 'produk_id'); }
}
