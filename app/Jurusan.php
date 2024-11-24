<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = "jurusan";
    protected $primaryKey = "id_jurusan";
    public $timestamps = false;
    protected $fillable = ["nama_jurusan", "status_jurusan"];

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class,"id_pengguna","id_jurusan");
    }

    public function batch()
    {
        return $this->hasMany(Batch::class,"id_jurusan","id_batch");
    }
}
