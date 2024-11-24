<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Topik as TopikModel;

class JadwalMahasiswa extends JsonResource
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
            'id_jadwal_mahasiswa' => $this->id_jadwal_mahasiswa,
            'hari_jadwal_mahasiswa' => $this->hari_jadwal_mahasiswa,
            'waktu_mulai_jadwal_mahasiswa' => $this->waktu_mulai_jadwal_mahasiswa,
            'waktu_selesai_jadwal_mahasiswa' => $this->waktu_selesai_jadwal_mahasiswa,
            'keterangan_jadwal_mahasiswa' => $this->keterangan_jadwal_mahasiswa,
            'id_topik' => Topik::collection(TopikModel::where('id_topik', $this->id_topik)->get())[0],
            'id_batch' => $this->id_batch
        ];
    }
}
