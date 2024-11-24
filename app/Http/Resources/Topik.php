<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Batch as BatchModel;
use App\Kbk as KbkModel;
use App\Pengguna as PenggunaModel;

class Topik extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $pengguna = PenggunaModel::where('kode_pengguna', $this->id_pengaju)->first();
        $pembimbing = PenggunaModel::where('kode_pengguna', $this->id_pembimbing)->first();
        $pembimbing_lapangan = PenggunaModel::where('kode_pengguna', $this->id_pembimbing_lapangan)->first();
        $penguji_sidang = PenggunaModel::where('kode_pengguna', $this->id_penguji_sidang)->first();
        $penguji_sidang_dua = PenggunaModel::where('kode_pengguna', $this->id_penguji_sidang_dua)->first();
        // return parent::toArray($request);
        return [
            'id_topik' => $this->id_topik,
            'judul_topik' => $this->judul_topik,
            'slug_topik' => $this->slug_topik,
            'latar_belakang' => $this->latar_belakang,
            'rumusan_masalah' => $this->rumusan_masalah,
            'tujuan_pembahasan' => $this->tujuan_pembahasan,
            'ruang_lingkup' => $this->ruang_lingkup,
            'sumber_data' => $this->sumber_data,
            'sistematika_penyajian' => $this->sistematika_penyajian,
            'transkrip_nilai' => $this->transkrip_nilai,
            'dkbs' => $this->dkbs,
            'tanggal_pengajuan_topik' => $this->tanggal_pengajuan_topik,
            'form_persetujuan_instansi' => $this->form_persetujuan_instansi,
            'lampiran_gambar' => $this->lampiran_gambar,
            'status_persetujuan_dosen' => $this->status_persetujuan_dosen,
            'status_topik' => $this->status_topik,
            'batch' => Batch::collection(BatchModel::where('id_batch', $this->id_batch)->get())[0],
            'kbk' => Kbk::collection(KbkModel::where('id_kbk', $this->id_kbk)->get())[0],
            'id_pengaju' => $this->id_pengaju,
            'nama' => $pengguna != null ? $pengguna->nama_pengguna : $this->id_pengaju,
            'pengguna' => $pengguna,
            'id_pembimbing' => $this->id_pembimbing,
            'pembimbing' => $pembimbing != null ? $pembimbing->nama_pengguna : $this->id_pembimbing,
            'id_pembimbing_lapangan' => $this->id_pembimbing_lapangan,
            'pembimbing_lapangan' => $pembimbing_lapangan != null ? $pembimbing_lapangan->nama_pengguna : $this->id_pembimbing_lapangan,
            'id_penguji_sidang' => $this->id_penguji_sidang,
            'penguji_sidang' => $penguji_sidang != null ? $penguji_sidang->nama_pengguna : $this->id_penguji_sidang,
            'id_penguji_sidang_dua' => $this->id_penguji_sidang_dua,
            'penguji_sidang_dua' => $penguji_sidang_dua != null ? $penguji_sidang_dua->nama_pengguna : $this->id_penguji_sidang_dua,
        ];
    }
}
