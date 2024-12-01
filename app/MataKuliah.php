<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{

    protected $table = 'mata_kuliah';
    protected $primaryKey = 'id_matakuliah';
    public $timestamps = false;

    protected $fillable = [
        'nama_matkul',
        'jumlah_sks',
        'status',
    ];

    public function jenis_semester()
    {
        return $this->belongsTo(JenisSemester::class, 'id_jenis_semester');
    }
}
