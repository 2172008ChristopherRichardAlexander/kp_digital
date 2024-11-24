<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenggunaRole extends Model
{
    protected $table = "pengguna_role";
    protected $primaryKey = "id_pengguna_role";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ["id_pengguna_role ", "nama_penguna_role", "status_pengguna_role"];


    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, "id_pengguna_role", "id_pengguna");
    }
}
