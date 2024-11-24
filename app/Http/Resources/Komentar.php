<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Komentar extends JsonResource
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
            'id_komentar' => $this->id_komentar,
            'isi_komentar' => $this->isi_komentar,
            'tanggal_komentar' => $this->tanggal_komentar,
            'id_topik' => $this->id_topik,
            'id_pengguna' => $this->id_pengguna
        ];
    }
}
