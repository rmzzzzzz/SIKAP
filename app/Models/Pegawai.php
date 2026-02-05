<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = ['nama', 'nip', 'email', 'telp', 'jabatan', 'unit_kerja', 'opd_id'];

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id', 'id_opd');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'pegawai_id', 'id_pegawai');
    }
     public function user()
    {
        return $this->hasOne(User::class);
    }
    public function kegiatanWajib()
{
    return $this->belongsToMany(
            Kegiatan::class,
            'kegiatan_pegawai',
            'pegawai_id',
            'kegiatan_id',
            'id_pegawai',
            'id_kegiatan'
        );
}

}
