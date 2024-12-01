<?php

namespace App\Http\Resources;

use App\JenisMBKM as JenisMBKMModel;
use App\Pengguna as PenggunaModel;
use App\Semester as SemesterModel;
use Illuminate\Http\Resources\Json\JsonResource;

class MBKM extends JsonResource
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
            'id_mbkm' => $this->id_mbkm,
            'nama_instansi' => $this->nama_instansi,
            'deskripsi' => $this->deskripsi,
            'status' => $this->status,
            'id_jenis_mbkm' => $this->id_jenis_mbkm,
            'id_pengguna' => $this->id_pengguna,
            'id_semester' => $this->id_semester,
            'jenis_mbkm' => new JenisMBKM(JenisMBKMModel::where('id_jenis_mbkm', $this->id_jenis_mbkm)->first()),
            'mahasiswa' => new Pengguna(PenggunaModel::where('id_pengguna', $this->id_pengguna)->first()),
            'semester' => new Semester(SemesterModel::where('id_semester', $this->id_semester)->first()),
        ];
    }
}
