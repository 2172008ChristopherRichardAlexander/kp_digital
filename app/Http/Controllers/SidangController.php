<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Sidang;
use App\Http\Requests;
use App\Http\Resources\Sidang as SidangResource;
use Illuminate\Support\Facades\DB;

class SidangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Sidang
        $sidang = Sidang::paginate(10);
        // Return collection of Sidang as a resource
        return SidangResource::collection($sidang);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function listHasilSidang(Request $request)
    {
        // TODO : Belum berdasarkan Role
        // ! List hasil akhir sidang berdasarkan Batch
        // ! Status dalam bentuk Array

        $status_sidang = explode(",", $request->input('status_sidang'));

        if ($request->input('id_pengguna')) {
            $id_pengguna = $request->input('id_pengguna');
            $matchThese = ['sidang.id_batch' => $request->input('id_batch')];
            $kumpulan_sidang = Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->wherein('sidang.status_sidang', $status_sidang)->where('sidang.id_penguji_sidang', $id_pengguna)->orwhere('sidang.id_penguji_sidang_dua', $id_pengguna)->orwhere('topik.id_pembimbing', $id_pengguna)->get();
        } else {
            $matchThese = ['id_batch' => $request->input('id_batch')];
            $kumpulan_sidang = Sidang::where($matchThese)->wherein('status_sidang', $status_sidang)->get();
        }
        $sidang = SidangResource::collection($kumpulan_sidang);
        if (sizeof($sidang) != 0) {
            return $sidang;
        }
        return ['message' => 'kosong'];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sidang = $request->isMethod('put') ? Sidang::find($request->id_sidang) : new Sidang;

        $sidang->ruangan_sidang = $request->input('ruangan_sidang');
        $sidang->tanggal_sidang = $request->input('tanggal_sidang');
        $sidang->waktu_mulai_sidang = $request->input('waktu_mulai_sidang');
        $sidang->waktu_akhir_sidang = $request->input('waktu_akhir_sidang');
        $sidang->nilai_pembimbing = $request->input('nilai_pembimbing');
        $sidang->nilai_pembimbing_lapangan = $request->input('nilai_pembimbing_lapangan');
        $sidang->nilai_penguji = $request->input('nilai_penguji');
        $sidang->nilai_penguji_dua = $request->input('nilai_penguji_dua');
        $sidang->nilai_koor_kp = $request->input('nilai_koor_kp');
        $sidang->text_revisi_pembimbing = $request->input('text_revisi_pembimbing');

        if (is_null($request->file('foto_revisi_pembimbing')) || empty($request->file('foto_revisi_pembimbing'))) {
            # code...
        } else {
            $sidang->foto_revisi_pembimbing = $this->uploadGambarPembimbing($request);
        }
        $sidang->text_revisi_penguji = $request->input('text_revisi_penguji');
        if (is_null($request->file('foto_revisi_penguji')) || empty($request->file('foto_revisi_penguji'))) {
            # code...
        } else {
            $sidang->foto_revisi_penguji = $this->uploadGambarPenguji($request);
        }

        $sidang->status_pembimbing_utama = $request->input('status_pembimbing_utama');
        $sidang->status_pembimbing_lapangan = $request->input('status_pembimbing_lapangan');
        $sidang->status_sidang = $request->input('status_sidang');
        $sidang->id_penguji_sidang = $request->input('id_penguji_sidang');
        $sidang->id_penguji_sidang_dua = $request->input('id_penguji_sidang_dua');
        $sidang->id_topik = $request->input('id_topik');
        $sidang->id_batch = $request->input('id_batch');
        $sidang->id_komponen_nilai = $request->input('id_komponen_nilai');
        $sidang->id_jenis_sidang = $request->input('id_jenis_sidang');
        $sidang->id_syarat_ketentuan_sidang_mahasiswa = $request->input('id_syarat_ketentuan_sidang_mahasiswa');
        $sidang->id_syarat_ketentuan_sidang_dosen = $request->input('id_syarat_ketentuan_sidang_dosen');
        $sidang->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sidang  $sidang
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Sidang
        $sidang = Sidang::where('id_sidang', $id)->first();

        // Return single Sidang as a Resource
        return new SidangResource($sidang);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sidang  $sidang
     * @return \Illuminate\Http\Response
     */
    public function edit(Sidang $sidang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sidang  $sidang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sidang $sidang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sidang  $sidang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sidang $sidang)
    {
        //
    }

    /**
     * Sidang Berdasarkan Id Topik
     */
    public function sidangTopik(Request $request)
    {
        $matchThese = ['id_topik' => $request->input('id_topik'), 'id_batch' => $request->input('id_batch'), 'id_komponen_nilai' => $request->input('id_komponen_nilai'), 'id_jenis_sidang' => $request->input('id_jenis_sidang')];
        $sidang = Sidang::where($matchThese)->get();
        return SidangResource::collection($sidang);
    }

    public function listSidangPengaju(Request $request)
    {
        $kumpulan_sidang = Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where('topik.id_pengaju', '=', $request->input('id_pengaju'))->get();
        // $kumpulan_sidang = Sidang::topik();
        // $kumpulan_sidang = Sidang::with('topik')->get();
        return SidangResource::collection($kumpulan_sidang);
    }

    public function revisiSidang(Request $request)
    {
        if (is_null($request->file('foto_revisi_pembimbing')) || empty($request->file('foto_revisi_pembimbing'))) {
            $updateThese = ['text_revisi_pembimbing' => $request->input('text_revisi_pembimbing')];
        } else {
            $foto_revisi_pembimbing = $this->uploadGambarPembimbing($request);
            $updateThese = ['foto_revisi_pembimbing' => $foto_revisi_pembimbing, 'text_revisi_pembimbing' => $request->input('text_revisi_pembimbing')];
        }
        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        Sidang::where($matchThese)->update($updateThese);
    }

    // ! ################# Dosen #################
    public function listPersetujuanSidangPembimbingUtama(Request $request)
    {
        $matchThese = ['sidang.status_pembimbing_utama' => 2, 'topik.id_pembimbing' => $request->input('id_pembimbing'), 'sidang.id_batch' => $request->input('id_batch')];
        $kumpulan_sidang = SidangResource::collection(Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function listHistoryPersetujuanSidangPembimbingUtama(Request $request)
    {
        $matchThese = ['topik.id_pembimbing' => $request->input('id_pembimbing'), 'sidang.id_batch' => $request->input('id_batch')];
        $kumpulan_sidang = SidangResource::collection(Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->where('sidang.status_pembimbing_utama', "!=", 2)->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function listPersetujuanSidangPembimbingLapangan(Request $request)
    {
        $matchThese = ['sidang.status_pembimbing_lapangan' => 2, 'topik.id_pembimbing_lapangan' => $request->input('id_pembimbing_lapangan'), 'sidang.id_batch' => $request->input('id_batch')];
        $kumpulan_sidang = SidangResource::collection(Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function listHistoryPersetujuanSidangPembimbingLapangan(Request $request)
    {
        $matchThese = ['topik.id_pembimbing_lapangan' => $request->input('id_pembimbing_lapangan'), 'sidang.id_batch' => $request->input('id_batch')];
        $kumpulan_sidang = SidangResource::collection(Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->where('sidang.status_pembimbing_lapangan', "!=", 2)->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function updateStatusSidangPembimbingUtama(Request $request)
    {
        $updateThese = ['status_pembimbing_utama' => $request->input('status_pembimbing_utama')];
        $matchThese = ['sidang.id_sidang' => $request->input('id_sidang'), 'topik.id_pembimbing' => $request->input('id_pembimbing')];
        Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->update($updateThese);

        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        $sidang = Sidang::where($matchThese)->first();
        if ($sidang->status_sidang == 2 && $sidang->status_pembimbing_utama == 1 && ($sidang->status_pembimbing_lapangan == 1 || $sidang->status_pembimbing_lapangan == 3)) {
            $sidang->status_sidang = 3;
            $sidang->save();
        }
    }

    public function updateStatusSidangPembimbingLapangan(Request $request)
    {
        $updateThese = ['status_pembimbing_lapangan' => $request->input('status_pembimbing_lapangan')];
        $matchThese = ['sidang.id_sidang' => $request->input('id_sidang'), 'topik.id_pembimbing_lapangan' => $request->input('id_pembimbing_lapangan')];
        Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->update($updateThese);

        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        $sidang = Sidang::where($matchThese)->first();
        if ($sidang->status_sidang == 2  && $sidang->status_pembimbing_utama == 1 && ($sidang->status_pembimbing_lapangan == 1 || $sidang->status_pembimbing_lapangan == 3)) {
            $sidang->status_sidang = 3;
            $sidang->save();
        }
    }

    public function listPembimbingUtama(Request $request)
    {
        $status_sidang = explode(",", $request->input('status_sidang'));
        $matchThese = ['sidang.id_batch' => $request->input('id_batch'), 'topik.id_pembimbing' => $request->input('id_pembimbing')];
        $kumpulan_sidang = SidangResource::collection(Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->whereIn('sidang.status_sidang', $status_sidang)->whereNull('nilai_pembimbing')->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function listHistoryPembimbingUtama(Request $request)
    {
        $status_sidang = [0, 1, 5];
        $matchThese = ['sidang.id_batch' => $request->input('id_batch'), 'topik.id_pembimbing' => $request->input('id_pembimbing')];
        $kumpulan_sidang = SidangResource::collection(Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->wherein('sidang.status_sidang', $status_sidang)->whereNotNull('sidang.nilai_pembimbing')->where($matchThese)->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function listPembimbingLapangan(Request $request)
    {
        $matchThese = ['sidang.status_pembimbing_lapangan' => $request->input('status_pembimbing_lapangan'), 'sidang.id_batch' => $request->input('id_batch')];
        if ($request->has('id_pembimbing_lapangan')) {
            $matchThese['topik.id_pembimbing_lapangan'] = $request->input('id_pembimbing_lapangan');
        }
        $kumpulan_sidang = SidangResource::collection(Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function listPenguji(Request $request)
    {
        $status_sidang = explode(",", $request->input('status_sidang'));
        $matchTheseOne = ['id_batch' => $request->input('id_batch'), 'id_penguji_sidang' => $request->input('id_penguji')];
        $matchTheseTwo = ['id_penguji_sidang_dua' => $request->input('id_penguji')];
        $kumpulan_sidang = SidangResource::collection(Sidang::whereNull('nilai_penguji')->whereIn('status_sidang', $status_sidang)->where($matchTheseOne)->orWhere($matchTheseTwo)->whereNull('nilai_penguji_dua')->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }


    public function listKonfirmasiRevisiPembimbingUtama(Request $request)
    {
        $matchThese = ['sidang.status_sidang' => $request->input('status_sidang'), 'sidang.id_batch' => $request->input('id_batch'), 'topik.id_pembimbing' => $request->input('id_pembimbing'), "sidang.status_revisi_pembimbing" => $request->input('status_revisi_pembimbing')];
        $kumpulan_sidang = SidangResource::collection(Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->whereNotNull('sidang.nilai_pembimbing')->whereNotNull('sidang.nilai_penguji')->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function listKonfirmasiRevisiPenguji(Request $request)
    {
        $status_sidang = explode(",", $request->input('status_sidang'));
        $matchThese = ['id_batch' => $request->input('id_batch'), "status_revisi_penguji" => $request->input('status_revisi_penguji')];
        $sidang = Sidang::whereIn('status_sidang', $status_sidang)->whereNotNull('nilai_pembimbing')->where($matchThese)->where(function ($query) use ($request) {
            $query->where('id_penguji_sidang', $request->input('id_penguji'))->whereNotNull('nilai_penguji');
        })->orWhere(function ($query) use ($request) {
            $query->where('id_penguji_sidang_dua', $request->input('id_penguji'))->whereNotNull('nilai_penguji_dua');
        })->get();
        $kumpulan_sidang = SidangResource::collection($sidang);
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function listHistoryPenguji(Request $request)
    {
        $status_sidang = [0, 1, 5];
        $matchTheseOne = ['id_batch' => $request->input('id_batch'), 'id_penguji_sidang' => $request->input('id_penguji')];
        $matchTheseTwo = ['id_penguji_sidang_dua' => $request->input('id_penguji')];
        $kumpulan_sidang = SidangResource::collection(Sidang::wherein('status_sidang', $status_sidang)->whereNotNull('nilai_penguji')->where($matchTheseOne)->orWhere($matchTheseTwo)->whereNotNull('nilai_penguji_dua')->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function penilaianPembimbingLapangan(Request $request)
    {
        $updateThese = ['nilai_pembimbing_lapangan' => $request->input('nilai_pembimbing_lapangan')];
        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        Sidang::where($matchThese)->update($updateThese);
    }

    public function penilaianRevisi(Request $request)
    {
        if (is_null($request->file('foto_revisi')) || empty($request->file('foto_revisi'))) {
            // $updateThese = ['status_sidang' => $request->input('status_sidang'), 'text_revisi' => $request->input('text_revisi'), $request->input('jenis_nilai_pengguna') => $request->input('nilai_total'), $request->input('jenis_nilai_pengguna') . '_non_program' => $request->input('nilai_total_non_program')];
            $updateThese = ['status_sidang' => $request->input('status_sidang'), 'text_revisi' => $request->input('text_revisi'), $request->input('jenis_nilai_pengguna') => $request->input('nilai_total')];
        } else {
            $foto_revisi = $this->uploadGambar($request);
            // $updateThese = ['status_sidang' => $request->input('status_sidang'), 'foto_revisi' => $foto_revisi, 'text_revisi' => $request->input('text_revisi'), $request->input('jenis_nilai_pengguna') => $request->input('nilai_total'), $request->input('jenis_nilai_pengguna') . '_non_program' => $request->input('nilai_total_non_program')];
            $updateThese = ['status_sidang' => $request->input('status_sidang'), 'foto_revisi' => $foto_revisi, 'text_revisi' => $request->input('text_revisi'), $request->input('jenis_nilai_pengguna') => $request->input('nilai_total')];
        }

        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        Sidang::where($matchThese)->update($updateThese);
    }


    public function uploadGambar(Request $request)
    {
        // Upload File
        $name_file = "noimage.png";
        if ($revisi_file = $request->file('foto_revisi')) {
            $destination_path = 'public/sidang/' . $request->input('id_pengaju') . '/' . 'foto_revisi/'; // upload path
            $name_file = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'revisi' . "." . $revisi_file->getClientOriginalExtension();
            $revisi_file->move($destination_path, $name_file);
        }
        return $name_file;
    }

    public function konfirmasiRevisi(Request $request)
    {
        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        $updateThese = [$request->input('jenis_konfirmasi') => $request->input('status_revisi')];
        Sidang::where($matchThese)->update($updateThese);
    }

    public function hasilPenilaianRevisi(Request $request)
    {
        $updateThese = ['status_sidang' => $request->input('status_sidang'), $request->input('jenis_nilai_pengguna') => $request->input('nilai_total')];

        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        $this->updateRevisiSidang($request);
        Sidang::where($matchThese)->update($updateThese);
    }

    public function updateRevisiSidang(Request $request)
    {
        $updateThese = ['status_sidang' => $request->input('status_sidang'), $request->input('jenis_text_revisi') => $request->input('text_revisi')];
        if (is_null($request->file("foto_revisi")) || empty($request->file('foto_revisi'))) {
        } else {
            if ($request->input('jenis_foto_revisi') == "foto_revisi_pembimbing") {
                $foto_revisi = $this->uploadGambarPembimbing($request);
            } else {
                $foto_revisi = $this->uploadGambarPenguji($request);
            }
            $updateThese = ['status_sidang' => $request->input('status_sidang'), $request->input('jenis_foto_revisi') => $foto_revisi, $request->input('jenis_text_revisi') => $request->input('text_revisi')];
        }

        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        Sidang::where($matchThese)->update($updateThese);
    }


    public function uploadGambarPembimbing(Request $request)
    {
        // Upload File
        $name_file = "noimage.png";
        if ($revisi_file = $request->file('foto_revisi')) {
            $destination_path = 'public/sidang/' . $request->input('id_pengaju') . '/' . 'foto_revisi/'; // upload path
            $name_file = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'revisi_pembimbing' . "." . $revisi_file->getClientOriginalExtension();
            $revisi_file->move($destination_path, $name_file);
        }
        return $name_file;
    }

    public function uploadGambarPenguji(Request $request)
    {
        // Upload File
        $name_file = "noimage.png";
        if ($revisi_file = $request->file('foto_revisi')) {
            $destination_path = 'public/sidang/' . $request->input('id_pengaju') . '/' . 'foto_revisi/'; // upload path
            $name_file = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'revisi_penguji' . "." . $revisi_file->getClientOriginalExtension();
            $revisi_file->move($destination_path, $name_file);
        }
        return $name_file;
    }

    public function listSidang(Request $request)
    {
        $status_sidang = explode(",", $request->input('status_sidang'));
        $matchThese = ['id_batch' => $request->input('id_batch')];

        $kumpulan_sidang = SidangResource::collection(Sidang::where($matchThese)->whereIn('status_sidang', $status_sidang)->get());
        if (sizeof($kumpulan_sidang) != 0) {
            return $kumpulan_sidang;
        }
        return ['message' => 'kosong'];
    }
    // ! ################# Koordinator KP #################
    public function listPengajuanSidang(Request $request)
    {
        $matchTheseOne = ['id_batch' => $request->input('id_batch'), 'status_sidang' => 3];
        $matchTheseTwo = ['id_batch' => $request->input('id_batch'), 'status_sidang' => 4];
        return SidangResource::collection(Sidang::where($matchTheseOne)->orWhere($matchTheseTwo)->get());
    }

    public function detailSidang(Request $request)
    {
        // $matchThese = ['id_batch' => $request->input('id_batch'), 'status_sidang' => 3];
        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        return SidangResource::collection(Sidang::where($matchThese)->get());
    }

    public function detailInfoSidang(Request $request)
    {
        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        return new SidangResource(Sidang::where($matchThese)->first());
    }

    public function detailSidangMahasiswa(Request $request)
    {
        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        return new SidangResource(Sidang::where($matchThese)->first());
    }

    public function listMahasiswa(Request $request)
    {
        // * List Mahasiswa yang sidangnya sudah di setujui oleh pembimbing utama dan atau pembimbing lapangan
        $matchTheseOne = ['status_pembimbing_utama' => 1, 'status_pembimbing_lapangan' => 1, 'id_batch' => $request->input('id_batch')];
        $matchTheseTwo = ['status_pembimbing_utama' => 1, 'status_pembimbing_lapangan' => 3, 'id_batch' => $request->input('id_batch')];
        return SidangResource::collection(Sidang::where($matchTheseOne)->orWhere($matchTheseTwo)->get());
    }

    public function daftarMahasiswa(Request $request)
    {
        // * List Mahasiswa berdasarkan status_pembimbing_utama, status_pembimbing_lapangan, id_batch
        $matchThese = ['id_batch' => $request->input('id_batch')];
        $status_pembimbing_utama = [1];
        $status_pembimbing_lapangan = [1, 3];
        if ($request->input('status_pembimbing_utama'))
            $status_pembimbing_utama = explode(",", $request->input('status_pembimbing_utama'));
        if ($request->input('status_pembimbing_lapangan'))
            $status_pembimbing_lapangan = explode(",", $request->input('status_pembimbing_lapangan'));
        $daftarMahasiswa = SidangResource::collection(Sidang::where($matchThese)->whereIn('status_pembimbing_utama', $status_pembimbing_utama)->whereIn('status_pembimbing_lapangan', $status_pembimbing_lapangan)->get());
        if (sizeof($daftarMahasiswa) != 0) {
            return $daftarMahasiswa;
        }
        return ['message' => 'kosong'];
    }

    public function updateNilaiMahasiswa(Request $request)
    {
        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        $updateThese = ['nilai_koor_kp' => $request->input('nilai_koor_kp')];
        Sidang::where($matchThese)->update($updateThese);
    }

    public function stepperSidang(Request $request)
    {
        return Sidang::select(DB::raw('count(id_sidang) as jumlah_data, status_sidang'))->where('id_batch', $request->input('id_batch'))->orderBy('status_sidang')->groupBy('status_sidang')->get();
    }

    public function deleteSidangMahasiswa(Request $request)
    {
        $sidang = Sidang::where('id_sidang', $request->input('id_sidang'))->first();
        $sidang->delete();
    }


    // ! ################# Koordinator KBK #################
    public function listSidangBatchKbk(Request $request)
    {
        // * List Sidang Berdasarkan Batch dan KBK
        $matchThese = ['sidang.id_batch' => $request->input('id_batch'), 'topik.id_kbk' => $request->input('id_kbk')];
        $kumpulan_sidang = Sidang::select('sidang.*')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->get();
        return SidangResource::collection($kumpulan_sidang);
    }
    public function updatePenguji(Request $request)
    {
        // * Update Penguji Sidang
        $matchThese = ['id_sidang' => $request->input('id_sidang')];
        $updateThese = ['id_penguji_sidang' => $request->input('id_penguji_sidang'), 'id_penguji_sidang_dua' => $request->input('id_penguji_sidang_dua')];
        Sidang::where($matchThese)->update($updateThese);
    }
}
