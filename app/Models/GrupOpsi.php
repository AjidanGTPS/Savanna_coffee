<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupOpsi extends Model
{
    protected $table = 'grup_opsi';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['nama', 'slug', 'tipe', 'wajib', 'maks_pilihan', 'urutan'];

    protected $casts = ['wajib' => 'boolean'];

    public function opsi() { return $this->hasMany(Opsi::class, 'grup_opsi_id')->where('tersedia', true)->orderBy('urutan'); }
    public function produk() { return $this->belongsToMany(Produk::class, 'produk_grup_opsi', 'grup_opsi_id', 'produk_id'); }
}
