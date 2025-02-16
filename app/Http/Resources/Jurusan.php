<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Jurusan extends JsonResource
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
            'id_jurusan'=>$this->id_jurusan,
            'nama_jurusan'=>$this->nama_jurusan,
            'status_jurusan'=>$this->status_jurusan
        ];
    }
}
