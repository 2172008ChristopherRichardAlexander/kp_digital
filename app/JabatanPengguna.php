<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JabatanPengguna extends Model
{
    protected $table = "jabatan_pengguna";
    protected $primaryKey = "id_jabatan_pengguna";
    public $timestamps = false;
    protected $fillable = ["id_pengguna","id_jabatan"];
    
    // public function pengguna()
    // {
    //     return $this->belongsTo(Pengguna::class,"id_pengguna","id_jabatan_pengguna");
    // }
    
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class,"id_jabatan_pengguna","id_jabatan");
    }
}
