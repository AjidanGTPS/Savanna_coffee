<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama', 'satuan', 'stok_saat_ini', 'stok_minimum',
        'stok_maksimum', 'harga_per_satuan', 'keterangan', 'aktif',
    ];

    protected $casts = ['aktif' => 'boolean'];

    public function transaksi()
    {
        return $this->hasMany(TransaksiBahanBaku::class, 'bahan_baku_id');
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->stok_saat_ini <= 0) return 'habis';
        if ($this->stok_saat_ini <= $this->stok_minimum) return 'kritis';
        if ($this->stok_minimum > 0 && $this->stok_saat_ini <= $this->stok_minimum * 1.5) return 'rendah';
        return 'aman';
    }

    public function getStatusStokLabelAttribute(): string
    {
        return match ($this->status_stok) {
            'habis'  => 'Habis',
            'kritis' => 'Kritis',
            'rendah' => 'Rendah',
            default  => 'Aman',
        };
    }
}
