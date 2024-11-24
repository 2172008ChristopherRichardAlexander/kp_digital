<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Topik as TopikModel;
use App\Batch as BatchModel;
use App\KomponenNilai as KomponenNilaiModel;
use App\JenisSidang as JenisSidangModel;
use App\Pengguna as PenggunaModel;
use App\SyaratKetentuanSidang as SyaratKetentuanSidangModel;

class Sidang extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $pengguna = (PenggunaModel::where('kode_pengguna', (TopikModel::where('id_topik', $this->id_topik)->first())->id_pengaju)->first());
        $penguji_sidang = (PenggunaModel::where('kode_pengguna', $this->id_penguji_sidang))->first();
        $penguji_sidang_dua = (PenggunaModel::where('kode_pengguna', $this->id_penguji_sidang_dua))->first();
        // return parent::toArray($request);
        return [
            'id_sidang' => $this->id_sidang,
            'ruangan_sidang' => $this->ruangan_sidang,
            'tanggal_sidang' => $this->tanggal_sidang,
            'waktu_mulai_sidang' => $this->waktu_mulai_sidang,
            'waktu_akhir_sidang' => $this->waktu_akhir_sidang,
            'nilai_pembimbing' => $this->nilai_pembimbing,
            // 'nilai_pembimbing_non_program' => $this->nilai_pembimbing_non_program,
            'nilai_pembimbing_lapangan' => $this->nilai_pembimbing_lapangan,
            'nilai_penguji' => $this->nilai_penguji,
            // 'nilai_penguji_non_program' => $this->nilai_penguji_non_program,
            'nilai_penguji_dua' => $this->nilai_penguji_dua,
            // 'nilai_penguji_dua_non_program' => $this->nilai_penguji_dua_non_program,
            'nilai_koor_kp' => $this->nilai_koor_kp,
            'text_revisi_pembimbing' => $this->text_revisi_pembimbing,
            'foto_revisi_pembimbing' => $this->foto_revisi_pembimbing,
            'text_revisi_penguji' => $this->text_revisi_penguji,
            'foto_revisi_penguji' => $this->foto_revisi_penguji,
            'status_pembimbing_utama' => $this->status_pembimbing_utama,
            'status_pembimbing_lapangan' => $this->status_pembimbing_lapangan,
            'status_revisi_pembimbing' => $this->status_revisi_pembimbing,
            'status_revisi_penguji' => $this->status_revisi_penguji,
            'status_sidang' => $this->status_sidang,
            'id_penguji_sidang' => $this->id_penguji_sidang,
            'penguji_sidang' => $penguji_sidang != null ? $penguji_sidang->nama_pengguna : $this->id_penguji_sidang,
            'id_penguji_sidang_dua' => $this->id_penguji_sidang_dua,
            'penguji_sidang_dua' => $penguji_sidang_dua != null ? $penguji_sidang_dua->nama_pengguna : $this->id_penguji_sidang_dua,
            'topik' => new Topik(TopikModel::where('id_topik', $this->id_topik)->first()),
            'batch' => new Batch(BatchModel::where('id_batch', $this->id_batch)->first()),
            'komponen_nilai' => new KomponenNilai(KomponenNilaiModel::where('id_komponen_nilai', $this->id_komponen_nilai)->first()),
            'jenis_sidang' => new JenisSidang(JenisSidangModel::where('id_jenis_sidang', $this->id_jenis_sidang)->first()),
            'syarat_ketentuan_sidang_mahasiswa' => new SyaratKetentuanSidang(SyaratKetentuanSidangModel::where('id_syarat_ketentuan_sidang', $this->id_syarat_ketentuan_sidang_mahasiswa)->first()),
            'syarat_ketentuan_sidang_dosen' => new SyaratKetentuanSidang(SyaratKetentuanSidangModel::where('id_syarat_ketentuan_sidang', $this->id_syarat_ketentuan_sidang_dosen)->first()),
            'nama' => $pengguna != null ? $pengguna->nama_pengguna : '',
            // 'id_topik' => Topik::collection(TopikModel::where('id_topik', $this->id_topik)->get())[0],
            // 'id_batch' => Batch::collection(BatchModel::where('id_batch', $this->id_batch)->get())[0],
            // 'id_komponen_nilai' => KomponenNilai::collection(KomponenNilaiModel::where('id_komponen_nilai', $this->id_komponen_nilai)->get())[0],
            // 'id_jenis_sidang' => JenisSidang::collection(JenisSidangModel::where('id_jenis_sidang', $this->id_jenis_sidang)->get())[0]
        ];
    }
}
