<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';
    public $timestamps = false;

    protected $fillable = [
        'file_dokumen',
        'is_template',
        'id_pengguna',
        'id_semester',
        'id_jenis_dokumen',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }

    public function jenis_dokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'id_jenis_dokumen');
    }
}
