<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pangkat extends Model
{
    protected $table = 'pangkat';
    protected $primaryKey = 'id_pangkat';

    protected $fillable = ['nama_pangkat'];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'pangkat_id', 'id_pangkat');
    }
}
