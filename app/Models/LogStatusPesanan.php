<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStatusPesanan extends Model
{
    protected $table = 'log_status_pesanan';

    public $timestamps = false;

    protected $fillable = ['pesanan_id', 'pengguna_id', 'status_awal', 'status_akhir', 'dibuat_pada'];

    protected $casts = ['dibuat_pada' => 'datetime'];

    public function pesanan() { return $this->belongsTo(Pesanan::class, 'pesanan_id'); }
    public function pengguna() { return $this->belongsTo(Pengguna::class, 'pengguna_id'); }
}
