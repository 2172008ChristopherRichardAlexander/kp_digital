<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalMahasiswa extends Model
{
    protected $table = "jadwal_mahasiswa";
    protected $primaryKey = "id_jadwal_mahasiswa";
    public $timestamps = false;
    protected $fillable = ['hari_jadwal_mahasiswa', 'waktu_mulai_jadwal_mahasiswa', 'waktu_selesai_jadwal_mahasiswa', 'keterangan_jadwal_mahasiswa', 'id_topik', 'id_batch'];

    public function topik()
    {
        return $this->belongsTo(Topik::class, "id_jadwal_mahasiswa", "id_topik");
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, "id_jadwal_mahasiswa", "id_batch");
    }
}
