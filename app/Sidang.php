<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sidang extends Model
{
    protected $table = "sidang";
    protected $primaryKey = "id_sidang";
    public $timestamps = false;
    protected $fillable = [
        "ruangan_sidang", "tanggal_sidang", "waktu_mulai_sidang", "waktu_akhir_sidang", "nilai_pembimbing",
        // "nilai_pembimbing_non_program", 
        "nilai_pembimbing_lapangan", "nilai_penguji",
        // "nilai_penguji_non_program", 
        "nilai_penguji_dua",
        // "nilai_penguji_dua_non_program", 
        "nilai_koor_kp", "text_revisi_pembimbing", "foto_revisi_pembimbing", "text_revisi_penguji", "foto_revisi_penguji", "status_pembimbing_utama", "status_pembimbing_lapangan", "status_revisi_pembimbing", "status_revisi_penguji", "status_sidang", "id_penguji_sidang", "id_penguji_sidang_dua", "id_topik", "id_batch", "id_komponen_nilai", "id_jenis_sidang", "id_syarat_ketentuan_sidang_mahasiswa", "id_syarat_ketentuan_sidang_dosen"
    ];

    public function komponenNilai()
    {
        return $this->belongsTo(KomponenNilai::class, 'id_komponen_nilai', 'id_sidang');
    }

    public function jenisSidang()
    {
        return $this->belongsTo(JenisSidang::class, 'id_jenis_sidang', 'id_sidang');
    }

    public function topik()
    {
        // return $this->hasMany(Topik::class, "id_topik", "id_sidang");
        return $this->belongsTo(Topik::class, "id_sidang", "id_topik");
    }
}
