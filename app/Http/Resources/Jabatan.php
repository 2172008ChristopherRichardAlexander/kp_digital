<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Jabatan extends JsonResource
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
            'id_jabatan' => $this->id_jabatan,
            'nama_jabatan' => $this->nama_jabatan,
            'status_jabatan' => $this->status_jabatan,
            'id_role' => $this->id_role,
            'id_kbk' => $this->id_kbk
        ];
    }
}
