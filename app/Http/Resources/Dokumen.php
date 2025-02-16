<?php

namespace App\Http\Resources;

use App\Pengguna as PenggunaModel;
use App\Semester as SemesterModel;
use App\JenisDokumen as JenisDokumenModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Dokumen extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id_dokumen' => $this->id_dokumen,
            'file_dokumen' => $this->file_dokumen,
            'is_template'=>$this->is_template,
            'id_pengguna' => $this->id_pengguna,
            'kode_pengguna'=>$this->kode_pengguna,
            'id_semester' => $this->id_semester,
            'id_jenis_dokumen'=> $this->id_jenis_dokumen,
            'mahasiswa' => new Pengguna(PenggunaModel::where('id_pengguna', $this->id_pengguna)->first()),
            'semester' => new Semester(SemesterModel::where('id_semester', $this->id_semester)->first()),
            'jenis_dokumen' => new JenisDokumen(JenisDokumenModel::where('id_jenis_dokumen', $this->id_jenis_dokumen)->first()),
        ];
    }
}
