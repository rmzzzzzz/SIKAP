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
        return $this->role === 'super_admin';
    }

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id', 'id_opd');
    }
}
