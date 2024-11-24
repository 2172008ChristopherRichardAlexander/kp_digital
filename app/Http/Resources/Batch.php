<?php

namespace App\Http\Resources;

use App\Semester as SemesterModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Batch extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id_batch' => $this->id_batch,
            'nama_batch' => $this->nama_batch,
            'tanggal_mulai_batch' => $this->tanggal_mulai_batch,
            'tanggal_selesai_batch' => $this->tanggal_selesai_batch,
            'jenis_batch' => $this->jenis_batch,
            'minimal_bimbingan' => $this->minimal_bimbingan,
            'status_batch' => $this->status_batch,
            'id_jurusan' => $this->id_jurusan,
            'semester' => new Semester(SemesterModel::where('id_semester', $this->id_semester)->first()),
        ];
    }
}
