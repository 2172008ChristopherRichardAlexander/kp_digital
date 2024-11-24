<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\KomponenPenilaian as KomponenPenilaianModel;
use App\Sidang as SidangModel;

class KomponenPenilaianSidang extends JsonResource
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
            'id_komponen_penilaian_sidang' => $this->id_komponen_penilaian_sidang,
            'komponen_penilaian' => new KomponenPenilaian(KomponenPenilaianModel::where('id_komponen_penilaian', $this->id_komponen_penilaian)->first()),
            'sidang' => new Sidang(SidangModel::where('id_sidang', $this->id_sidang)->first()),
            'nilai_komponen_penilaian_sidang' => $this->nilai_komponen_penilaian_sidang,
        ];
    }
}
