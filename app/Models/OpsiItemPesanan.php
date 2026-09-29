<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpsiItemPesanan extends Model
{
    protected $table = 'opsi_item_pesanan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['item_pesanan_id', 'opsi_id', 'nama_grup_opsi', 'nama_opsi', 'harga_tambahan'];

    public function itemPesanan() { return $this->belongsTo(ItemPesanan::class, 'item_pesanan_id'); }
    public function opsi() { return $this->belongsTo(Opsi::class, 'opsi_id'); }
}
