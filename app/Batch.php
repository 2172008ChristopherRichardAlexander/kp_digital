<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
	protected $table = "batch";
	protected $primaryKey = "id_batch";
	public $timestamps = false;
	protected $fillable = ["nama_batch", "tanggal_mulai_batch", "tanggal_selesai_batch", "jenis_batch", "minimal_bimbingan", "status_batch", "id_jurusan", "id_semester"];

	public function topik()
	{
		return $this->hasMany(Topik::class, "id_batch", "id_topik");
	}

	public function jurusan()
	{
		return $this->belongsTo(Jurusan::class, "id_batch", "id_jurusan");
	}

	public function semester()
	{
		return $this->belongsTo(Semester::class, "id_batch", "id_semester");
	}
}
