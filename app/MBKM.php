<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MBKM extends Model
{

    protected $table = 'mbkm';
    protected $primaryKey = 'id_mbkm';
    public $timestamps = false;

    protected $fillable = [
        'nama_instansi',
        'deskripsi',
        'status',
        'id_jenis_mbkm',
        'id_pengguna',
        'id_semester',
    ];

    public function jenis_mbkm()
    {
        return $this->belongsTo(JenisMBKM::class, 'id_jenis_mbkm');
    }
    public function mahasiswa()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }

}
