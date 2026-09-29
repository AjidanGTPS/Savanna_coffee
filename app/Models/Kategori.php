<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['induk_id', 'nama', 'slug', 'deskripsi', 'gambar', 'urutan', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function induk() { return $this->belongsTo(Kategori::class, 'induk_id'); }
    public function anak() { return $this->hasMany(Kategori::class, 'induk_id'); }
    public function produk() { return $this->hasMany(Produk::class, 'kategori_id'); }
    public function produkAktif() { return $this->hasMany(Produk::class, 'kategori_id')->where('tersedia', true)->orderBy('urutan'); }
}
