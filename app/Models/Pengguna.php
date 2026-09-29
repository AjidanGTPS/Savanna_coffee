<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['nama', 'email', 'telepon', 'kata_sandi', 'peran', 'aktif', 'token_ingat'];

    protected $hidden = ['kata_sandi', 'token_ingat'];

    protected $rememberTokenName = 'token_ingat';

    protected $casts = ['aktif' => 'boolean', 'dibuat_pada' => 'datetime', 'diperbarui_pada' => 'datetime'];

    public function getAuthPasswordName(): string { return 'kata_sandi'; }
    public function getAuthPassword(): string { return $this->kata_sandi; }
    public function getRememberTokenName(): string { return 'token_ingat'; }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->peran, (array) $roles);
    }

    public function isAdmin(): bool { return $this->peran === 'admin'; }
    public function isKasir(): bool { return $this->peran === 'kasir'; }
    public function isPelayan(): bool { return $this->peran === 'pelayan'; }
    public function isOwner(): bool { return $this->peran === 'owner'; }
    public function isManajer(): bool { return $this->peran === 'manajer'; }

    public function getPeranLabelAttribute(): string
    {
        return match ($this->peran) {
            'admin'   => 'Admin',
            'kasir'   => 'Kasir',
            'pelayan' => 'Pelayan',
            'owner'   => 'Owner',
            'manajer' => 'Manajer',
            default   => $this->peran,
        };
    }

    public function pesanan() { return $this->hasMany(Pesanan::class, 'pengguna_id'); }
    public function pembayaran() { return $this->hasMany(Pembayaran::class, 'kasir_id'); }
    public function sesiMeja() { return $this->hasMany(SesiMeja::class, 'dibuka_oleh'); }
}
