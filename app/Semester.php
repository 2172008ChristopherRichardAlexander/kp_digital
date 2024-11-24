<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = "semester";
    protected $primaryKey = "id_semester";
    public $timestamps = false;
    protected $fillable = ["nama_semester", "tanggal_mulai_semester", "tanggal_selesai_semester", "status_semester"];

    public function batch()
    {
        return $this->hasMany(Batch::class, "id_semester", "id_batch");
    }
}
