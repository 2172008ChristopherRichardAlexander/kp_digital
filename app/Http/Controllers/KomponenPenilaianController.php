<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\KomponenPenilaian;
use App\Http\Requests;
use App\Http\Resources\KomponenPenilaian as KomponenPenilaianResource;

class KomponenPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get KomponenPenilaian
        $komponen = KomponenPenilaian::paginate(5);
        // Return collection of KomponenPenilaian as a resource
        return KomponenPenilaianResource::collection($komponen);
    }

    public function komponenPenilaianAktif()
    {
        // Mendapatkan Komponen Penilaian Berdasarkan Role
        $matchThese = ['status_komponen_penilaian' => 1];
        // Get KomponenPenilaian
        $komponen = KomponenPenilaian::where($matchThese)->get();
        // Return collection of KomponenPenilaian as a resource
        return KomponenPenilaianResource::collection($komponen);
    }



    // ! ################# Koordinator KP #################
    public function listKomponenPenilaianRole($role)
    {
        // Mendapatkan Komponen Penilaian Berdasarkan Role
        $matchThese = ['id_role' => $role, 'status_komponen_penilaian' => 0];
        // Get KomponenPenilaian
        $komponen = KomponenPenilaian::where($matchThese)->paginate(5);
        // Return collection of KomponenPenilaian as a resource
        return KomponenPenilaianResource::collection($komponen);
    }

    public function listKomponenPenilaian(Request $request)
    {
        // Mendapatkan Komponen Penilaian Berdasarkan Role dan Status
        $matchThese = ['id_role' => $request->input('id_role'), 'status_komponen_penilaian' => $request->input('status_komponen_penilaian')];
        // Get KomponenPenilaian
        if ($request->input('status_komponen_penilaian') == 1) {
            $komponen = KomponenPenilaian::where($matchThese)->get();
        } else {
            $komponen = KomponenPenilaian::where($matchThese)->paginate(5);
        }
        // Return collection of KomponenPenilaian as a resource
        return KomponenPenilaianResource::collection($komponen);
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
        $komponen = $request->isMethod('put') ? KomponenPenilaian::find($request->id_komponen_penilaian) : new KomponenPenilaian;

        $komponen->judul_komponen_penilaian = $request->input('judul_komponen_penilaian');
        $komponen->deskripsi_komponen_penilaian = $request->input('deskripsi_komponen_penilaian');
        $komponen->bobot_komponen_penilaian = $request->input('bobot_komponen_penilaian');
        $komponen->status_komponen_penilaian = $request->input('status_komponen_penilaian');
        $komponen->id_role = $request->input('id_role');
        $komponen->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KomponenPenilaian  $komponenPenilaian
     * @return \Illuminate\Http\Response
     */
    public function show($id_komponen_penilaian)
    {
        // Get Komponen Penilaian
        $komponen_penilaian = KomponenPenilaian::where('slug_topik', $id_komponen_penilaian)->first();

        // Return single Komponen Penilaian as a Resource
        return new KomponenPenilaianResource($komponen_penilaian);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KomponenPenilaian  $komponenPenilaian
     * @return \Illuminate\Http\Response
     */
    public function edit(KomponenPenilaian $komponenPenilaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KomponenPenilaian  $komponenPenilaian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KomponenPenilaian $komponenPenilaian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KomponenPenilaian  $komponenPenilaian
     * @return \Illuminate\Http\Response
     */
    public function destroy(KomponenPenilaian $komponenPenilaian)
    {
        //
    }


    // ! ################# Dosen #################
    public function komponenPenilaian($role)
    {
        // Mendapatkan Komponen Penilaian Berdasarkan Role
        $matchThese = ['id_role' => $role, 'status_komponen_penilaian' => 1];
        // Get KomponenPenilaian
        $komponen = KomponenPenilaian::where($matchThese)->get();
        // Return collection of KomponenPenilaian as a resource
        return KomponenPenilaianResource::collection($komponen);
    }

    public function komponenPenilaianRole(Request $request)
    {
        // Mendapatkan Komponen Penilaian Berdasarkan Role
        $matchThese = ['id_role' => $request->input('id_role'), 'status_komponen_penilaian' => 1];
        // Get KomponenPenilaian
        $komponen = KomponenPenilaian::where($matchThese)->get();
        // Return collection of KomponenPenilaian as a resource
        return KomponenPenilaianResource::collection($komponen);
    }
}
