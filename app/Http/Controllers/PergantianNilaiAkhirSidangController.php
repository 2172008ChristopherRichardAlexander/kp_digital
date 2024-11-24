<?php

namespace App\Http\Controllers;

use App\PergantianNilaiAkhirSidang;
use Illuminate\Http\Request;
use App\Http\Resources\PergantianNilaiAkhirSidang as PergantianNilaiAkhirSidangResource;

class PergantianNilaiAkhirSidangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Menentukan Perintah Insert("Post") atau Update("Put")
        $pergantian = $request->isMethod('put') ? PergantianNilaiAkhirSidang::where('id_pergantian_nilai_akhir_sidang', $request->id_pergantian_nilai_akhir_sidang)->first() : new PergantianNilaiAkhirSidang;

        $pergantian->nilai_awal = $request->input('nilai_awal');
        $pergantian->nilai_ubah = $request->input('nilai_ubah');
        $pergantian->status_pembimbing_pergantian_nilai_akhir_sidang = $request->input('status_pembimbing_pergantian_nilai_akhir_sidang');
        $pergantian->status_penguji_pergantian_nilai_akhir_sidang = $request->input('status_penguji_pergantian_nilai_akhir_sidang');
        $pergantian->status_penguji_dua_pergantian_nilai_akhir_sidang = $request->input('status_penguji_dua_pergantian_nilai_akhir_sidang');
        $pergantian->status_koordinator_kp_pergantian_nilai_akhir_sidang = $request->input('status_koordinator_kp_pergantian_nilai_akhir_sidang');
        $pergantian->id_sidang = $request->input('id_sidang');
        $pergantian->id_koordinator_kp = $request->input('id_koordinator_kp');
        $pergantian->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PergantianNilaiAkhirSidang  $pergantianNilaiAkhirSidang
     * @return \Illuminate\Http\Response
     */
    public function show(PergantianNilaiAkhirSidang $pergantianNilaiAkhirSidang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PergantianNilaiAkhirSidang  $pergantianNilaiAkhirSidang
     * @return \Illuminate\Http\Response
     */
    public function edit(PergantianNilaiAkhirSidang $pergantianNilaiAkhirSidang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PergantianNilaiAkhirSidang  $pergantianNilaiAkhirSidang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PergantianNilaiAkhirSidang $pergantianNilaiAkhirSidang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PergantianNilaiAkhirSidang  $pergantianNilaiAkhirSidang
     * @return \Illuminate\Http\Response
     */
    public function destroy(PergantianNilaiAkhirSidang $pergantianNilaiAkhirSidang)
    {
        //
    }

    public function listPergantianNilaiSidangAkhirRole(Request $request)
    {
        // ! Untuk mendapatkan semua data permintaan / history pergantian nilai sidang akhir berdasarkan role dan id pengguna
        // ! status dalam bentuk array
        // ! Pergantian Nilai Akhir Sidang terbaru
        $status = explode(",", $request->input('status'));
        if ($request->input('jenis_role') == 'pembimbing') {
            $matchThese = ['topik.id_pembimbing' => $request->input('id_pengguna'), 'sidang.id_batch' => $request->input('id_batch')];
            $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidangResource::collection(PergantianNilaiAkhirSidang::select('pergantian_nilai_akhir_sidang.*')->join('sidang', 'sidang.id_sidang', '=', 'pergantian_nilai_akhir_sidang.id_sidang')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->wherein($request->input('status_pergantian_nilai_akhir'), $status)->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->get());
        } else if ($request->input('jenis_role') == 'penguji') {
            $matchThese = ['sidang.id_penguji_sidang' => $request->input('id_pengguna'), 'sidang.id_batch' => $request->input('id_batch')];
            $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidangResource::collection(PergantianNilaiAkhirSidang::select('pergantian_nilai_akhir_sidang.*')->join('sidang', 'sidang.id_sidang', '=', 'pergantian_nilai_akhir_sidang.id_sidang')->where($matchThese)->wherein($request->input('status_pergantian_nilai_akhir'), $status)->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->get());
        } else if ($request->input('jenis_role') == 'penguji_dua') {
            $matchThese = ['sidang.id_penguji_dua_sidang' => $request->input('id_pengguna'), 'sidang.id_batch' => $request->input('id_batch')];
            $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidangResource::collection(PergantianNilaiAkhirSidang::select('pergantian_nilai_akhir_sidang.*')->join('sidang', 'sidang.id_sidang', '=', 'pergantian_nilai_akhir_sidang.id_sidang')->where($matchThese)->wherein($request->input('status_pergantian_nilai_akhir'), $status)->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->get());
        } else if ($request->input('jenis_role') == 'koordinator_kp') {
            $matchThese = ['sidang.id_batch' => $request->input('id_batch')];
            $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidangResource::collection(PergantianNilaiAkhirSidang::select('pergantian_nilai_akhir_sidang.*')->join('sidang', 'sidang.id_sidang', '=', 'pergantian_nilai_akhir_sidang.id_sidang')->where($matchThese)->wherein($request->input('status_pergantian_nilai_akhir'), $status)->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->get());
        }

        if (sizeof($pergantian_nilai_akhir_sidang) != 0) {
            return $pergantian_nilai_akhir_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function listPergantianNilaiSidangAkhirSidang(Request $request)
    {
        // ! Untuk mendapatkan semua data permintaan / history pergantian nilai sidang akhir berdasarkan role, id pengguna, id sidang
        // ! status dalam bentuk array
        // ! Pergantian Nilai Akhir Sidang terbaru
        if ($request->input('jenis_role') == 'pembimbing') {
            $matchThese = ['pergantian_nilai_akhir_sidang.id_sidang' => $request->input('id_sidang'), 'topik.id_pembimbing' => $request->input('id_pengguna'), 'sidang.id_batch' => $request->input('id_batch')];
            $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidangResource::collection(PergantianNilaiAkhirSidang::select('pergantian_nilai_akhir_sidang.*')->join('sidang', 'sidang.id_sidang', '=', 'pergantian_nilai_akhir_sidang.id_sidang')->join('topik', 'topik.id_topik', '=', 'sidang.id_topik')->where($matchThese)->wherein('status_pergantian_nilai_akhir', $request->input('status'))->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->get());
        } else if ($request->input('jenis_role') == 'penguji') {
            $matchThese = ['pergantian_nilai_akhir_sidang.id_sidang' => $request->input('id_sidang'), 'sidang.id_penguji_sidang' => $request->input('id_pengguna'), 'sidang.id_batch' => $request->input('id_batch')];
            $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidangResource::collection(PergantianNilaiAkhirSidang::select('pergantian_nilai_akhir_sidang.*')->join('sidang', 'sidang.id_sidang', '=', 'pergantian_nilai_akhir_sidang.id_sidang')->where($matchThese)->wherein('status_pergantian_nilai_akhir', $request->input('status'))->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->get());
        } else if ($request->input('jenis_role') == 'penguji dua') {
            $matchThese = ['pergantian_nilai_akhir_sidang.id_sidang' => $request->input('id_sidang'), 'sidang.id_penguji_dua_sidang' => $request->input('id_pengguna'), 'sidang.id_batch' => $request->input('id_batch')];
            $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidangResource::collection(PergantianNilaiAkhirSidang::select('pergantian_nilai_akhir_sidang.*')->join('sidang', 'sidang.id_sidang', '=', 'pergantian_nilai_akhir_sidang.id_sidang')->where($matchThese)->wherein('status_pergantian_nilai_akhir', $request->input('status'))->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->get());
        } else if ($request->input('jenis_role') == 'koordinator kp') {
            $matchThese = ['pergantian_nilai_akhir_sidang.id_sidang' => $request->input('id_sidang'), 'sidang.id_batch' => $request->input('id_batch')];
            $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidangResource::collection(PergantianNilaiAkhirSidang::select('pergantian_nilai_akhir_sidang.*')->join('sidang', 'sidang.id_sidang', '=', 'pergantian_nilai_akhir_sidang.id_sidang')->where($matchThese)->wherein('status_pergantian_nilai_akhir', $request->input('status'))->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->get());
        }

        if (sizeof($pergantian_nilai_akhir_sidang) != 0) {
            return $pergantian_nilai_akhir_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function pergantianNilaiSidangTerakhirDiSetujui(Request $request)
    {
        // ! Untuk mendapatkan data pergantian nilai sidang terakhir berdasarkan id sidang
        // ! Sudah di setujui oleh pembimbing, penguji, penguji dua(jika ada), koordinator KP
        // ! Pergantian Nilai Akhir Sidang terbaru
        $status_penguji_dua = [1, 3];
        $matchThese = ['id_sidang' => $request->input('id_sidang'), 'status_pembimbing_pergantian_nilai_akhir_sidang' => 1, 'status_penguji_pergantian_nilai_akhir_sidang' => 1, 'status_koordinator_kp_pergantian_nilai_akhir_sidang' => 1];
        $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidang::where($matchThese)->whereIn('status_penguji_dua_pergantian_nilai_akhir_sidang', $status_penguji_dua)->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->first();


        // if (!!$pergantian_nilai_akhir_sidang) {
        if (!is_null($pergantian_nilai_akhir_sidang) || !empty($pergantian_nilai_akhir_sidang)) {
            return new PergantianNilaiAkhirSidangResource($pergantian_nilai_akhir_sidang);
        }
        return ['message' => 'kosong'];
    }

    // public function getPergantianNilaiSidangTerakhirDiSetujui($id_sidang)
    // {
    //     // ! Untuk mendapatkan data pergantian nilai sidang terakhir berdasarkan id sidang
    //     // ! Sudah di setujui oleh pembimbing, penguji, penguji dua(jika ada), koordinator KP
    //     // ! Pergantian Nilai Akhir Sidang terbaru

    //     $matchThese = ['id_sidang' => $id_sidang];
    //     $pergantian_nilai_akhir_sidang = PergantianNilaiAkhirSidang::where($matchThese)->orderBy('id_pergantian_nilai_akhir_sidang', 'desc')->first();


    //     if (!!$pergantian_nilai_akhir_sidang) {
    //         return new PergantianNilaiAkhirSidangResource($pergantian_nilai_akhir_sidang);
    //     }
    //     return ['nilai_ubah' => 80];
    //     return ['message' => 'kosong'];
    // }

    public function updateNilai(Request $request)
    {
        // ! Update Nilai Awal dan Akhir Pergantian Nilai Akhir Sidang
        $matchThese = ['id_pergantian_nilai_akhir_sidang' => $request->input('id_pergantian_nilai_akhir_sidang')];
        $updateThese = ['nilai_awal' => $request->input('nilai_awal'), 'nilai_ubah' => $request->input('nilai_ubah')];
        PergantianNilaiAkhirSidang::where($matchThese)->update($updateThese);
    }

    public function updateStatus(Request $request)
    {
        // ! Update Status Pergantian Nilai Akhir Sidang Berdasarkan Jenis Status
        $matchThese = ['id_pergantian_nilai_akhir_sidang' => $request->input('id_pergantian_nilai_akhir_sidang')];
        $updateThese = [$request->input('jenis_status') => $request->input('status')];
        PergantianNilaiAkhirSidang::where($matchThese)->update($updateThese);
    }

    public function updateStatusKoorKp(Request $request)
    {
        // ! Update Status Pergantian Nilai Akhir Sidang Berdasarkan Jenis Status
        $matchThese = ['id_pergantian_nilai_akhir_sidang' => $request->input('id_pergantian_nilai_akhir_sidang')];
        $updateThese = ['status_koordinator_kp_pergantian_nilai_akhir_sidang' => $request->input('status'), 'id_koordinator_kp' => $request->input('id_koordinator_kp')];
        PergantianNilaiAkhirSidang::where($matchThese)->update($updateThese);
    }
}
