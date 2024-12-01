<?php

namespace App\Http\Resources;

use App\Pengguna as PenggunaModel;
use App\Semester as SemesterModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Logbook extends JsonResource
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
            'id_logbook' => $this->id_logbook,
            'file_logbook' => $this->file_logbook,
            'id_pengguna' => $this->id_pengguna,
            'id_mbkm' => $this->id_mbkm,
            'mahasiswa' => new Pengguna(PenggunaModel::where('id_pengguna', $this->id_pengguna)->first()),
            'mbkm' => new MBKM(MBKMModel::where('id_mbkm', $this->id_mbkm)->first()),
        ];
    }
}
