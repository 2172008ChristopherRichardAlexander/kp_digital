<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JenisSemester extends JsonResource
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
            'id_jenis_semester' => $this->id_jenis_semester,
            'nama_semester' => $this->nama_semester,
            'status' => $this->status,
        ];
    }
}
