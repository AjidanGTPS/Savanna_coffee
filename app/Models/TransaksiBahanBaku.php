<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiBahanBaku extends Model
{
    protected $table = 'transaksi_bahan_baku';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'bahan_baku_id', 'pengguna_id', 'jenis',
        'jumlah', 'stok_sebelum', 'stok_sesudah', 'keterangan', 'dicatat_pada',
    ];

    protected $casts = ['dicatat_pada' => 'datetime'];

    public function bahanBaku() { return $this->belongsTo(BahanBaku::class, 'bahan_baku_id'); }
    public function pengguna()  { return $this->belongsTo(Pengguna::class, 'pengguna_id'); }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'masuk'        => 'Stok Masuk',
            'keluar'       => 'Stok Keluar',
            'penyesuaian'  => 'Penyesuaian',
            default        => $this->jenis,
        };
    }
}
