<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Topik as TopikModel;

class Bimbingan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $topik = new Topik(TopikModel::where('id_topik', $this->id_topik)->first());
        $pengguna = $topik != null ? $topik->pengguna : null;
        // return parent::toArray($request);
        return [
            'id_bimbingan' => $this->id_bimbingan,
            'deskripsi_bimbingan' => $this->deskripsi_bimbingan,
            'tanggal_bimbingan' => $this->tanggal_bimbingan,
            'status_bimbingan' => $this->status_bimbingan,
            'id_topik' => Topik::collection(TopikModel::where('id_topik', $this->id_topik)->get())[0],
            'pengguna' => $pengguna,
            'id_pembimbing_bimbingan' => $this->id_pembimbing_bimbingan
        ];
    }
}
