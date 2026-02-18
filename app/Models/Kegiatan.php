<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KegiatanPegawai;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id_kegiatan';

    protected $fillable = [
        'opd_id',
        'nama_kegiatan',
        'pic',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'latitude',
        'longitude',
        'akses_kegiatan',
        'kehadiran',
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
    public function laporan()
{
    return $this->hasMany(Laporan::class, 'id_kegiatan', 'id_kegiatan');
}
   public function pegawaiWajib()
{
    return $this->belongsToMany(
        \App\Models\Pegawai::class,
        'kegiatan_pegawai',
        'kegiatan_id',
        'pegawai_id'
    );
}
public function pimpinan()
{
    return $this->pegawai
        ->map->user          // ambil user dari tiap pegawai
        ->filter()           // buang null
        ->firstWhere('role', 'pimpinan');
}



}
