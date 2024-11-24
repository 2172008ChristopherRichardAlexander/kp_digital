<?php

namespace App\Http\Resources;

use App\Jurusan as JurusanModel;
use App\PenggunaRole as PenggunaRoleModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Pengguna extends JsonResource
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
            'id_pengguna' => $this->id_pengguna,
            'nama_pengguna' => $this->nama_pengguna,
            'kota_lahir' => $this->kota_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'password' => $this->password,
            'kode_pengguna' => $this->kode_pengguna,
            'photo_profile' => $this->photo_profile,
            'nomor_hp' => $this->nomor_hp,
            'nomor_telepon' => $this->nomor_telepon,
            'email' => $this->email,
            'kontak_lainnya' => $this->kontak_lainnya,
            'linked_in_url' => $this->linked_in_url,
            'id_jurusan' => $this->id_jurusan,
            'jurusan' => new Jurusan(JurusanModel::where('id_jurusan', $this->id_jurusan)->first()),
            'id_pengguna_role' => $this->id_pengguna_role,
            'pengguna_role' => new PenggunaRole(PenggunaRoleModel::where('id_pengguna_role', $this->id_pengguna_role)->first()),
        ];
    }
}
