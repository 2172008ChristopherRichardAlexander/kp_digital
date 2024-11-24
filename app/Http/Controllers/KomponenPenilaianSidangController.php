<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\KomponenPenilaianSidang;
use App\Http\Requests;
use App\Http\Resources\KomponenPenilaianSidang as KomponenPenilaianSidangResource;

class KomponenPenilaianSidangController extends Controller
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
        $komponen =  new KomponenPenilaianSidang;

        $komponen->id_komponen_penilaian = $request->input('id_komponen_penilaian');
        $komponen->id_sidang = $request->input('id_sidang');
        $komponen->nilai_komponen_penilaian_sidang = $request->input('nilai_komponen_penilaian_sidang');
        $komponen->save();
    }

    public function updateKomponenPenilaianSidang(Request $request)
    {
        $matchThese = ['id_komponen_penilaian_sidang' => $request->input('id_komponen_penilaian_sidang')];
        $komponen = KomponenPenilaianSidang::where($matchThese)->first();
        $komponen->nilai_komponen_penilaian_sidang = $request->input('nilai_komponen_penilaian_sidang');
        $komponen->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KomponenPenilaianSidang  $komponenPenilaianSidang
     * @return \Illuminate\Http\Response
     */
    public function show(KomponenPenilaianSidang $komponenPenilaianSidang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KomponenPenilaianSidang  $komponenPenilaianSidang
     * @return \Illuminate\Http\Response
     */
    public function edit(KomponenPenilaianSidang $komponenPenilaianSidang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KomponenPenilaianSidang  $komponenPenilaianSidang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KomponenPenilaianSidang $komponenPenilaianSidang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KomponenPenilaianSidang  $komponenPenilaianSidang
     * @return \Illuminate\Http\Response
     */
    public function destroy(KomponenPenilaianSidang $komponenPenilaianSidang)
    {
        //
    }

    public function getKomponenPenilaianSidangRole(Request $request)
    {
        $matchThese = ['sidang.id_sidang' => $request->input('id_sidang'), 'komponen_penilaian.id_role' => $request->input('id_role')];
        $komponen_penilaian_sidang = KomponenPenilaianSidangResource::collection(KomponenPenilaianSidang::select('komponen_penilaian_sidang.*')->join('sidang', 'sidang.id_sidang', '=', 'komponen_penilaian_sidang.id_sidang')->join('komponen_penilaian', 'komponen_penilaian.id_komponen_penilaian', '=', 'komponen_penilaian_sidang.id_komponen_penilaian')->where($matchThese)->get());
        if (sizeof($komponen_penilaian_sidang) != 0) {
            return $komponen_penilaian_sidang;
        }
        return ['message' => 'kosong'];
    }

    public function insertDataKomponenPenilaian(Request $request)
    {
        $id_sidang = $request->input("id_sidang");
        $data = array();
        foreach ($request->input("komponen_penilaian") as $komponen) {
            if (!empty($komponen)) {
                $data[] = ['id_komponen_penilaian' => $komponen['id_komponen_penilaian'], 'id_sidang' => $id_sidang, 'nilai_komponen_penilaian_sidang' => 0];
            }
        }
        KomponenPenilaianSidang::insert($data);
    }
}
