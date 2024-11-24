<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = "pengguna";
    protected $primaryKey = "id_pengguna";
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ["id_pengguna", "nama_pengguna", "kota_lahir", "tanggal_lahir", "password", "kode_pengguna", "photo_profile", "nomor_hp", "nomor_telepon", "email", "kontak_lainnya", "linked_in_url", "id_jurusan", "id_pengguna_role"];

    // public function jabatanPengguna(){
    //     return $this->hasMany(JabatanPengguna::class);
    // }


    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, "id_pengguna", "id_jurusan");
    }
    public function penggunaRole()
    {
        return $this->belongsTo(PenggunaRole::class, "id_pengguna", "id_pengguna_role");
    }
}
