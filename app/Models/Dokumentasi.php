<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $table = 'dokumentasi';
    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'laporan_id',
        'path',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id_laporan');
    }
}
