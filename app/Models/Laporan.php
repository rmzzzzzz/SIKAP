<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'kegiatan_id',
        'opd_id',
        'total_hadir',
        'status_persetujuan',
        'ttd_pimpinan',
        'waktu_persetujuan',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'id_kegiatan');
    }

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id', 'id_opd');
    }
}

