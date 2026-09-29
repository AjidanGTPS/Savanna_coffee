<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiMeja extends Model
{
    protected $table = 'sesi_meja';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['meja_id', 'dibuka_oleh', 'nama_pelanggan', 'jumlah_tamu', 'status', 'dibuka_pada', 'ditutup_pada'];

    protected $casts = ['dibuka_pada' => 'datetime', 'ditutup_pada' => 'datetime'];

    public function meja() { return $this->belongsTo(Meja::class, 'meja_id'); }
    public function pembuka() { return $this->belongsTo(Pengguna::class, 'dibuka_oleh'); }
    public function pesanan() { return $this->hasMany(Pesanan::class, 'sesi_meja_id'); }
    public function pembayaran() { return $this->hasOne(Pembayaran::class, 'sesi_meja_id'); }

    public function getSubtotalAttribute(): int
    {
        return $this->pesanan->where('status', '!=', 'dibatalkan')->sum('subtotal');
    }
}
