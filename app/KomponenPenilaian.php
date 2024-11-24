<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KomponenPenilaian extends Model
{
    protected $table = "komponen_penilaian";
    protected $primaryKey = "id_komponen_penilaian";
    public $timestamps = false;
    protected $fillable = ["judul_komponen_penilaian", "deskripsi_komponen_penilaian", "bobot_komponen_penilaian", "status_komponen_penilaian", "id_role"];


    public function role()
    {
        return $this->belongsTo(Role::class, "id_role", "id_komponen_penilaian");
    }
}
