<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Kbk extends JsonResource
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
            'id_kbk'=>$this->id_kbk,
            'nama_kbk'=>$this->nama_kbk,
            'status_kbk'=>$this->status_kbk
        ];
    }
}
