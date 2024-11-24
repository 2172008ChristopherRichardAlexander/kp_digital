<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    protected $table = "bimbingan";
    protected $primaryKey = "id_bimbingan";
    public $timestamps = false;
    protected $fillable = ["deskripsi_bimbingan", "tanggal_bimbingan", "status_bimbingan", "id_topik", "id_pembimbing_bimbingan"];

    public function topik()
    {
        return $this->belongsTo(Topik::class, "id_bimbingan", "id_topik");
    }
}
