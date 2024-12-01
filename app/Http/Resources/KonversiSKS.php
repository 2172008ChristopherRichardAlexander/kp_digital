<?php

namespace App\Http\Resources;

use App\Matakuliah as MatakuliahModel;
use App\Pengguna as PenggunaModel;
use App\Semester as SemesterModel;
use Illuminate\Http\Resources\Json\JsonResource;

class KonversiSKS extends JsonResource
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
            'id_matakuliah_detail' => $this->id_matakuliah_detail,
            'id_matakuliah' => $this->id_matakuliah,
            'id_pengguna' => $this->id_pengguna,
            'id_semester' => $this->id_semester,
            'matakuliah' => new Matakuliah(MatakuliahModel::where('id_matakuliah', $this->id_matakuliah)->first()),
            'mahasiswa' => new Pengguna(PenggunaModel::where('id_pengguna', $this->id_pengguna)->first()),
            'semester' => new Semester(SemesterModel::where('id_semester', $this->id_semester)->first()),
        ];
    }
}
