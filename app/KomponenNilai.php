<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KomponenNilai extends Model
{
    protected $table = "komponen_nilai";
    protected $primaryKey = "id_komponen_nilai";
    public $timestamps = false;
    protected $fillable = ["nama_komponen_nilai", "pembimbing", "pembimbing_lapangan", "penguji", "koordinator_kp", "status_komponen_nilai"];

    public function sidang()
    {
        return $this->hasMany(Sidang::class,"id_komponen_nilai","id_sidang");    
    }
}
