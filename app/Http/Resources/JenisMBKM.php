<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JenisMBKM extends JsonResource
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
            'id_jenis_mbkm' => $this->id_jenis_mbkm,
            'nama_mbkm' => $this->nama_mbkm,
        ];
    }
}
