<?php

namespace App\Models;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
      protected $fillable = [
        'pegawai_id','name','email','password','role'
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
        return $this->hasOneThrough(
            Opd::class,        
            Pegawai::class,    // Model perantara (Pegawai)
            'id_pegawai',      // Foreign key di tabel pegawai (yang connect ke user)
            'id_opd',              // Primary key di tabel opd
            'pegawai_id',      // Local key di tabel users
            'opd_id' 
            );// Foreign key di tabel pegawai (yang connect ke opd)    
    }
}
