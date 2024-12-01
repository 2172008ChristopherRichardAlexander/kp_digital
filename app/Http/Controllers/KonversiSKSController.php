<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\KonversiSKS;
use App\Http\Requests;
use App\Http\Resources\KonversiSKS as KonversiSKSResource;

class KonversiSKSController extends Controller
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

    public function getAllByIdPengguna($id_pengguna)
    {
        if ($id_pengguna != null) {
            // Ambil data MBKM yang berhubungan dengan semester tertentu
            $kumpulan_konversi = KonversiSKSResource::collection(KonversiSKS::where('id_pengguna', $id_pengguna)->get());
        }
        return $kumpulan_konversi;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->all() as $data) {
            // Jika $data adalah array
            if (is_array($data)) {
                $id_matakuliah = $data['id_matakuliah'];
                $id_pengguna = $data['id_pengguna'];
                $id_semester = $data['id_semester'];
            } else {
                // Jika $data adalah objek, gunakan input()
                $id_matakuliah = $data->input('id_matakuliah');
                $id_pengguna = $data->input('id_pengguna');
                $id_semester = $data->input('id_semester');
            }
            // Tentukan apakah ini update atau create
            $konversiSKS = $request->isMethod('put') && $request->id_matakuliah_detail ? KonversiSKS::find($request->id_matakuliah_detail) : new KonversiSKS;
            $konversiSKS->id_matakuliah = $id_matakuliah;
            $konversiSKS->id_pengguna = $id_pengguna;
            $konversiSKS->id_semester = $id_semester;
            $konversiSKS->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_pengguna)
    {
        foreach ($request->all() as $data) {
            KonversiSKS::where('id_pengguna',  $id_pengguna)->update(['id_matakuliah' => $data->id_matakuliah]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
