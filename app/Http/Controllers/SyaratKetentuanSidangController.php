<?php

namespace App\Http\Controllers;

use App\SyaratKetentuanSidang;
use Illuminate\Http\Request;
use App\Http\Resources\SyaratKetentuanSidang as SyaratKetentuanSidangResource;

class SyaratKetentuanSidangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get SyaratKetentuanSidang
        $komponen = SyaratKetentuanSidang::paginate(5);
        // Return collection of SyaratKetentuanSidang as a resource
        return SyaratKetentuanSidangResource::collection($komponen);
    }

    // ! ################# Koordinator KP #################
    public function listSyaratKetentuanSidangRole($role)
    {
        // Mendapatkan Syarat Ketentuan Sidang Berdasarkan Role
        $matchThese = ['id_role' => $role];
        // Get SyaratKetentuanSidang
        $komponen = SyaratKetentuanSidang::where($matchThese)->orderBy('status_syarat_ketentuan_sidang', 'desc')->paginate(5);
        // Return collection of SyaratKetentuanSidang as a resource
        return SyaratKetentuanSidangResource::collection($komponen);
    }

    public function listSyaratKetentuanSidangAktifRole($role)
    {
        // Mendapatkan Syarat Ketentuan Sidang Berdasarkan Role
        $matchThese = ['id_role' => $role, 'status_syarat_ketentuan_sidang' => 1];
        // Get SyaratKetentuanSidang
        $komponen = SyaratKetentuanSidang::where($matchThese)->get();
        // Return collection of SyaratKetentuanSidang as a resource
        return SyaratKetentuanSidangResource::collection($komponen);
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
        $komponen = $request->isMethod('put') ? SyaratKetentuanSidang::find($request->id_syarat_ketentuan_sidang) : new SyaratKetentuanSidang;

        $komponen->judul_syarat_ketentuan_sidang = $request->input('judul_syarat_ketentuan_sidang');
        $komponen->deskripsi_syarat_ketentuan_sidang = $request->input('deskripsi_syarat_ketentuan_sidang');
        $komponen->status_syarat_ketentuan_sidang = $request->input('status_syarat_ketentuan_sidang');
        $komponen->id_role = $request->input('id_role');
        $komponen->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SyaratKetentuanSidang  $syaratKetentuanSidang
     * @return \Illuminate\Http\Response
     */
    public function show(SyaratKetentuanSidang $syaratKetentuanSidang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SyaratKetentuanSidang  $syaratKetentuanSidang
     * @return \Illuminate\Http\Response
     */
    public function edit(SyaratKetentuanSidang $syaratKetentuanSidang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SyaratKetentuanSidang  $syaratKetentuanSidang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SyaratKetentuanSidang $syaratKetentuanSidang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SyaratKetentuanSidang  $syaratKetentuanSidang
     * @return \Illuminate\Http\Response
     */
    public function destroy(SyaratKetentuanSidang $syaratKetentuanSidang)
    {
        //
    }

    // ! ################# Dosen & Mahasiswa #################
    public function syaratKetentuanSidang($role)
    {
        // Mendapatkan Syarat Komponen Penilaian Role
        $matchThese = ['id_role' => $role, 'status_syarat_ketentuan_sidang' => 1];
        // Get KomponenPenilaian
        $komponen = SyaratKetentuanSidang::where($matchThese)->get();
        // Return collection of SyaratKetentuanSidang as a resource
        return SyaratKetentuanSidangResource::collection($komponen);
    }
}
