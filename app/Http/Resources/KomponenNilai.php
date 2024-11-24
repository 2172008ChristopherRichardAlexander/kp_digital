<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KomponenNilai extends JsonResource
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
        return[
            'id_komponen_nilai'=>$this->id_komponen_nilai,
            'nama_komponen_nilai'=>$this->nama_komponen_nilai,
            'pembimbing'=>$this->pembimbing,
            'pembimbing_lapangan'=>$this->pembimbing_lapangan,
            'penguji'=>$this->penguji,
            'koordinator_kp'=>$this->koordinator_kp,
            'status_komponen_nilai'=>$this->status_komponen_nilai
        ];
    }
}
