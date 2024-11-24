<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "role";
    protected $primaryKey = "id_role";
    public $timestamps = false;
    protected $fillable = ['nama_role','status_role'];

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class,"id_role","id_jabatan");
    }
}
