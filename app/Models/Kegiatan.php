<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id_kegiatan';

    protected $fillable = [
        'opd_id','nama_kegiatan','pic','waktu','lokasi',
        'latitude','longitude','akses_kegiatan'
    ];

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id', 'id_opd');
    }
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pic', 'id_pegawai');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'kegiatan_id', 'id_kegiatan');
    }
}
