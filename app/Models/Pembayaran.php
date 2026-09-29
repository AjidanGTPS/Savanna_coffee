<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nomor_faktur', 'sesi_meja_id', 'kasir_id',
        'subtotal', 'persen_pajak', 'jumlah_pajak',
        'persen_layanan', 'jumlah_layanan', 'jumlah_diskon', 'total',
        'metode', 'penyedia', 'nomor_referensi',
        'jumlah_dibayar', 'kembalian', 'status', 'dibayar_pada',
    ];

    protected $casts = ['dibayar_pada' => 'datetime'];

    public function sesiMeja() { return $this->belongsTo(SesiMeja::class, 'sesi_meja_id'); }
    public function kasir() { return $this->belongsTo(Pengguna::class, 'kasir_id'); }

    public function getMetodeLabelAttribute(): string
    {
        return match ($this->metode) {
            'tunai'          => 'Tunai',
            'kartu'          => 'Kartu',
            'dompet_digital' => 'E-Wallet',
            'qris'           => 'QRIS',
            default          => $this->metode,
        };
    }

    public function getNominalQrisAttribute(): string
    {
        return sprintf(
            'SAVANA|%s|%d',
            $this->nomor_faktur ?? 'PENDING',
            $this->total ?? 0
        );
    }

    public static function generateNomorFaktur(): string
    {
        $prefix = 'INV';
        $date = now()->format('ymd');
        $last = static::whereDate('dibayar_pada', today())->latest('id')->first();
        $seq = $last ? ((int) substr($last->nomor_faktur, -3)) + 1 : 1;
        return $prefix.$date.str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}
