<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';
    protected $primaryKey = 'id_kehadiran';

    protected $fillable = [
        'pegawai_id','kegiatan_id','tipe_pegawai','status_pegawai',
        'waktu_hadir','latitude_hadir','longitude_hadir','tanda_tangan'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id_pegawai');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'id_kegiatan');
    }
}
