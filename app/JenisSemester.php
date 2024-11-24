<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisSemester extends Model
{
    protected $table = 'jenis_semester';
    protected $primaryKey = 'id_semester';
    protected $fillable = ["nama_semester", "status_semester"];
    
}
