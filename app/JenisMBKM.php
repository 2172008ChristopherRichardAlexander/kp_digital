<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisMBKM extends Model
{
    protected $table = 'jenis_mbkm';
    protected $primaryKey = 'id_jenis_mbkm';
    protected $fillable = ["nama_mbkm"];
}
