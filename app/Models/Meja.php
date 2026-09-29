<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $table = 'meja';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['nomor', 'nama', 'barcode', 'kapasitas', 'area', 'status', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function sesiMeja() { return $this->hasMany(SesiMeja::class, 'meja_id'); }

    public function sesiAktif() { return $this->hasOne(SesiMeja::class, 'meja_id')->where('status', 'buka'); }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'kosong' => 'Kosong',
            'terisi' => 'Terisi',
            'dipesan' => 'Dipesan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'kosong' => 'green',
            'terisi' => 'red',
            'dipesan' => 'yellow',
            default => 'gray',
        };
    }
}
