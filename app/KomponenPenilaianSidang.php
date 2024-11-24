<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KomponenPenilaianSidang extends Model
{
    protected $table = "komponen_penilaian_sidang";
    /**
     * primaryKey 
     * 
     * @var integer
     * @access protected
     */
    protected $primaryKey = "id_komponen_penilaian_sidang";
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    // public $incrementing = false;

    protected $fillable = ["id_komponen_penilaian", "id_sidang", "nilai_komponen_penilaian_sidang"];
}
