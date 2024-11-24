<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = "komentar";
    protected $primaryKey = "id_komentar";
    public $timestamps = false;
    protected $fillable = ["isi_komentar","tanggal_komentar","id_topik","id_pengguna"];

    public function topik()
    {
        return $this->belongsTo(Topik::class,"id_komentar","id_topik");
    }

    // public function pengguna()
    // {
    //     return $this->belongsTo(Pengguna::class,"id_pengguna","id_topik");
    // }
}
