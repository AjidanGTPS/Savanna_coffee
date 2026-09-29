<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';
    const DELETED_AT = 'dihapus_pada';

    protected $fillable = ['kategori_id', 'nama', 'slug', 'deskripsi', 'gambar', 'harga_dasar', 'punya_varian', 'tersedia', 'urutan'];

    protected $casts = ['punya_varian' => 'boolean', 'tersedia' => 'boolean'];

    public function kategori() { return $this->belongsTo(Kategori::class, 'kategori_id'); }

    public function varian() { return $this->hasMany(VarianProduk::class, 'produk_id')->where('tersedia', true)->orderBy('urutan'); }

    public function grupOpsi()
    {
        return $this->belongsToMany(GrupOpsi::class, 'produk_grup_opsi', 'produk_id', 'grup_opsi_id')
            ->withPivot('urutan')
            ->orderBy('produk_grup_opsi.urutan');
    }

    public function getHargaMinAttribute(): int
    {
        if ($this->punya_varian && $this->relationLoaded('varian') && $this->varian->count()) {
            return $this->varian->min('harga');
        }
        return $this->harga_dasar;
    }
}
