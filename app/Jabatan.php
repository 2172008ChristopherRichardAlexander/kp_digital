<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = "jabatan";
    protected $primaryKey = "id_jabatan";
    public $timestamps = false;
    protected $fillable = ["nama_jabatan","status_jabatan","id_role","id_kbk"];

    public function jabatanPengguna(){
        return $this->hasMany(JabatanPengguna::class,'id_jabatan_pengguna','id_jabatan');
    }

    public function role()
    {
        return $this->belongsTo(Role::class,"id_jabatan","id_role");
    }
    
    public function kbk()
    {
        return $this->belongsTo(Kbk::class,"id_jabatan","id_kbk");
    }
}
