<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table = 'logbook';
    protected $primaryKey = 'id_logbook';
    public $timestamps = false;

    protected $fillable = [
        'file_logbook',
        'id_pengguna',
        'id_mbkm',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function mbkm()
    {
        return $this->belongsTo(Pengguna::class, 'id_mbkm');
    }
}