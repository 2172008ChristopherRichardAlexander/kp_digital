<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Sidang as SidangModel;

class PergantianNilaiAkhirSidang extends JsonResource
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
            'id_pergantian_nilai_akhir_sidang' => $this->id_pergantian_nilai_akhir_sidang,
            'nilai_awal' =>  $this->nilai_awal,
            'nilai_ubah' => $this->nilai_ubah,
            'status_pembimbing_pergantian_nilai_akhir_sidang' => $this->status_pembimbing_pergantian_nilai_akhir_sidang,
            'status_penguji_pergantian_nilai_akhir_sidang' => $this->status_penguji_pergantian_nilai_akhir_sidang,
            'status_penguji_dua_pergantian_nilai_akhir_sidang' => $this->status_penguji_dua_pergantian_nilai_akhir_sidang,
            'status_koordinator_kp_pergantian_nilai_akhir_sidang' => $this->status_koordinator_kp_pergantian_nilai_akhir_sidang,
            'sidang' => new Sidang(SidangModel::where('id_sidang', $this->id_sidang)->first()),
            'id_koordinator_kp' => $this->id_koordinator_kp,
        ];
    }
}
