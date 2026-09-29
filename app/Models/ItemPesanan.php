<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    protected $table = 'item_pesanan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'pesanan_id', 'produk_id', 'varian_produk_id',
        'nama_produk', 'nama_varian',
        'jumlah', 'harga_satuan', 'harga_opsi', 'subtotal', 'catatan',
    ];

    public function pesanan() { return $this->belongsTo(Pesanan::class, 'pesanan_id'); }
    public function produk() { return $this->belongsTo(Produk::class, 'produk_id'); }
    public function varian() { return $this->belongsTo(VarianProduk::class, 'varian_produk_id'); }
    public function opsi() { return $this->hasMany(OpsiItemPesanan::class, 'item_pesanan_id'); }
}
