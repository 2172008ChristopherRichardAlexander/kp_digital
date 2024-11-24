<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Semester extends JsonResource
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
            'id_semester' => $this->id_semester,
            'nama_semester' => $this->nama_semester,
            'tanggal_mulai_semester' => $this->tanggal_mulai_semester,
            'tanggal_selesai_semester' => $this->tanggal_selesai_semester,
            'status_semester' => $this->status_semester,
        ];
    }
}
