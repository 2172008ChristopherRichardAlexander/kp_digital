<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JenisDokumen extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id_jenis_dokumen' => $this->id_jenis_dokumen,
            'nama_dokumen' => $this->nama_dokumen,
        ];
    }
}
