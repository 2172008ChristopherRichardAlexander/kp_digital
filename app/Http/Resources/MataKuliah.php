<?php

namespace App\Http\Resources;

use App\JenisSemester as JenisSemesterModel;
use Illuminate\Http\Resources\Json\JsonResource;

class MataKuliah extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id_matakuliah' => $this->id_matakuliah,
            'nama_matakul' => $this->nama_matakul,
            'jumlah_sks' => $this->jumlah_sks,
            'status' => $this->status,
            'id_jenis_semester' => new JenisSemester(JenisSemesterModel::where('id_jenis_semester', $this->id_jenis_semester)->first()),
        ];
    }
}
