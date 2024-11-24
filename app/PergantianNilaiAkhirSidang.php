<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PergantianNilaiAkhirSidang extends Model
{
    protected $table = "pergantian_nilai_akhir_sidang";
    protected $primaryKey = "id_pergantian_nilai_akhir_sidang";
    public $timestamps = false;
    protected $fillable = [
        'nilai_awal',
        'nilai_ubah',
        'status_pembimbing_pergantian_nilai_akhir_sidang',
        'status_penguji_pergantian_nilai_akhir_sidang',
        'status_penguji_dua_pergantian_nilai_akhir_sidang',
        'status_koordinator_kp_pergantian_nilai_akhir_sidang',
        'id_sidang',
        'id_koordinator_kp',
    ];
}
