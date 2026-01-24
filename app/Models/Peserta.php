<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $table = 'peserta';
    protected $primaryKey = 'id_peserta';

    protected $fillable = [
        'opd_id','nama','nip','jabatan','unit_kerja','email','telp'
    ];

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id', 'id_opd');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'peserta_id', 'id_peserta');
    }
}
