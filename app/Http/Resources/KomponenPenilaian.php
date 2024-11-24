<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KomponenPenilaian extends JsonResource
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
            'id_komponen_penilaian' => $this->id_komponen_penilaian,
            'judul_komponen_penilaian' => $this->judul_komponen_penilaian,
            'deskripsi_komponen_penilaian' => $this->deskripsi_komponen_penilaian,
            'bobot_komponen_penilaian' => $this->bobot_komponen_penilaian,
            'status_komponen_penilaian' => $this->status_komponen_penilaian,
            'id_role' => $this->id_role,
        ];
    }
}
