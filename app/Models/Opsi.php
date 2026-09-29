<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opsi extends Model
{
    protected $table = 'opsi';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['grup_opsi_id', 'nama', 'harga_tambahan', 'bawaan', 'tersedia', 'urutan'];

    protected $casts = ['bawaan' => 'boolean', 'tersedia' => 'boolean'];

    public function grupOpsi() { return $this->belongsTo(GrupOpsi::class, 'grup_opsi_id'); }
}
