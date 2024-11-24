<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topik extends Model
{
    protected $table = "topik";
    protected $primaryKey = "id_topik";
    public $timestamps = false;
    protected $fillable = [
        'judul_topik', 'slug_topik', 'latar_belakang', 'rumusan_masalah', 'tujuan_pembahasan', 'ruang_lingkup',
        'sumber_data', 'sistematika_penyajian', 'transkrip_nilai', 'dkbs', 'tanggal_pengajuan_topik',
        'form_persetujuan_instansi', 'lampiran_gambar', 'status_persetujuan_dosen', 'status_topik', 'id_batch', 'id_kbk',
        'id_pengaju', 'id_pembimbing', 'id_pembimbing_lapangan', 'id_penguji_sidang', 'id_penguji_sidang_dua'
    ];

    public function komentar()
    {
        return $this->hasMany(Komentar::class, "id_topik", "id_komentar");
    }

    public function bimbingan()
    {
        return $this->hasMany(Bimbingan::class, "id_topik", "id_bimbingan");
    }

    public function sidang()
    {
        return $this->hasMany(Sidang::class, "id_topik", "id_sidang");
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, "id_topik", "id_batch");
    }

    public function kbk()
    {
        return $this->belongsTo(Kbk::class, "id_topik", "id_kbk");
    }
}

