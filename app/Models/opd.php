<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    protected $table = 'opd';
    protected $primaryKey = 'id_opd';

    protected $fillable = [
        'nama_opd'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'opd_id', 'id_opd');
    }

    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'opd_id', 'id_opd');
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'opd_id', 'id_opd');
    }
}
