<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SyaratKetentuanSidang extends Model
{
    protected $table = "syarat_ketentuan_sidang";
    protected $primaryKey = "id_syarat_ketentuan_sidang";
    public $timestamps = false;
    protected $fillable = ["id_syarat_ketentuan_sidang", "judul_syarat_ketentuan_sidang", "deskripsi_syarat_ketentuan_sidang", "status_syarat_ketentuan_sidang", "id_role"];
}
