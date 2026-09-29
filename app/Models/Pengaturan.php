<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['kunci', 'nilai'];

    public static function ambil(string $kunci, mixed $default = null): mixed
    {
        return static::where('kunci', $kunci)->value('nilai') ?? $default;
    }
}
