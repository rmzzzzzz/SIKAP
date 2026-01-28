<?php

namespace App\Models;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
      protected $fillable = [
        'opd_id','name','email','password','role'
    ];

    protected $hidden = ['password'];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'super_admin' || $this->role === 'operator' || $this->role === 'pimpinan';
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id_pegawai');
    }
     public function opd()
    {
        return $this->pegawai?->opd();
    }
}
