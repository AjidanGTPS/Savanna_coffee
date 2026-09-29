<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nomor_pesanan', 'sesi_meja_id', 'meja_id', 'pengguna_id',
        'status', 'catatan', 'subtotal',
        'dipesan_pada', 'dimasak_pada', 'siap_pada', 'diantar_pada',
    ];

    protected $casts = [
        'dipesan_pada' => 'datetime',
        'dimasak_pada' => 'datetime',
        'siap_pada' => 'datetime',
        'diantar_pada' => 'datetime',
    ];

    public function sesiMeja() { return $this->belongsTo(SesiMeja::class, 'sesi_meja_id'); }
    public function meja() { return $this->belongsTo(Meja::class, 'meja_id'); }
    public function pengguna() { return $this->belongsTo(Pengguna::class, 'pengguna_id'); }
    public function items() { return $this->hasMany(ItemPesanan::class, 'pesanan_id'); }
    public function logStatus() { return $this->hasMany(LogStatusPesanan::class, 'pesanan_id'); }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'baru' => 'Baru',
            'dimasak' => 'Dimasak',
            'siap' => 'Siap',
            'diantar' => 'Diantar',
            'dibatalkan' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'baru' => 'red',
            'dimasak' => 'yellow',
            'siap' => 'green',
            'diantar' => 'blue',
            'dibatalkan' => 'gray',
            default => 'gray',
        };
    }

    public static function generateNomor(): string
    {
        $prefix = 'PSN';
        $date = now()->format('ymd');
        $last = static::whereDate('dipesan_pada', today())->latest('id')->first();
        $seq = $last ? ((int) substr($last->nomor_pesanan, -3)) + 1 : 1;
        return $prefix.$date.str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}
