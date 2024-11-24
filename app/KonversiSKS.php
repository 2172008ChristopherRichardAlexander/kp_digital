<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KonversiSKS extends Model
{
    use HasFactory;

    protected $table = 'konversi_sks';
    protected $primaryKey = 'id_matakuliah_detail';
    public $timestamps = false;

    protected $fillable = [
        
        'id_matakuliah',
        'id_pengguna',
        'id_jenis_semester',
    ];

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'id_matakuliah');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function semester()
    {
        return $this->belongsTo(JenisSemester::class, 'id_jenis_semester');
    }
}
