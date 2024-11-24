<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JenisSidang extends JsonResource
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
            'id_jenis_sidang'=> $this->id_jenis_sidang,
            'nama_jenis_sidang'=> $this->nama_jenis_sidang,
            'komponen_nilai_jenis_sidang'=> $this->komponen_nilai_jenis_sidang,
            'status_jenis_sidang'=> $this->status_jenis_sidang
        ];
    }
}
