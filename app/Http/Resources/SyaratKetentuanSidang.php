<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SyaratKetentuanSidang extends JsonResource
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
            'id_syarat_ketentuan_sidang' => $this->id_syarat_ketentuan_sidang,
            'judul_syarat_ketentuan_sidang' => $this->judul_syarat_ketentuan_sidang,
            'deskripsi_syarat_ketentuan_sidang' => $this->deskripsi_syarat_ketentuan_sidang,
            'status_syarat_ketentuan_sidang' => $this->status_syarat_ketentuan_sidang,
            'id_role' => $this->id_role,
        ];
    }
}
