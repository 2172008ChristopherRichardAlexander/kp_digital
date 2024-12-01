<?php

namespace App\Http\Resources;

use App\Pengguna as PenggunaModel;
use App\Semester as SemesterModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Laporan extends JsonResource
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
            'id_laporan' => $this->id_laporan,
            'file_laporan' => $this->file_laporan,
            'deskripsi_laporan' => $this->deskripsi_laporan,
            'id_pengguna' => $this->id_pengguna,
            'id_semester' => $this->id_semester,
            'mahasiswa' => new Pengguna(PenggunaModel::where('id_pengguna', $this->id_pengguna)->first()),
            'semester' => new Semester(SemesterModel::where('id_semester', $this->id_semester)->first()),
        ];
    }
}
