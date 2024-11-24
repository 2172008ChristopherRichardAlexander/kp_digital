<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Jabatan as JabatanModel;

class JabatanPengguna extends JsonResource
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
            'id_jabatan_pengguna' => $this->id_jabatan_pengguna,
            'id_pengguna' => $this->id_pengguna,
            'jabatan' => Jabatan::collection(JabatanModel::where('id_jabatan', $this->id_jabatan)->get())[0]
        ];
    }
}
