<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kbk extends Model
{
    protected $table = "kbk";
    protected $primaryKey = "id_kbk";
    public $timestamps = false;
    protected $fillable = ['nama_kbk', 'status_kbk'];

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class,"id_kbk","id_jabatan");
    }

    public function topik()
    {
        return $this->hasMany(Topik::class,"id_kbk","id_topik");
    }
}
