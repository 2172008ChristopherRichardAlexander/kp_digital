<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisSidang extends Model
{
    protected $table = "jenis_sidang";
    protected $primaryKey = "id_jenis_sidang";
    public $timestamps = false;
    protected $fillable = ["nama_jenis_sidang","komponen_nilai_jenis_sidang","status_jenis_sidang"];

    public function sidang()
    {
        return $this->hasOne(Sidang::class,"id_jenis_sidang","id_sidang");
    }
}
