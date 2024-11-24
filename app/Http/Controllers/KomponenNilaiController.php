<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\KomponenNilai;
use App\Http\Requests;
use App\Http\Resources\KomponenNilai as KomponenNilaiResource;

class KomponenNilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Komponen Nilai
        $komponenNilai = KomponenNilai::paginate(10);
        // Return collection of Komponen Nilai as a resource
        return KomponenNilaiResource::collection($komponenNilai);
    }

    public function newest()
    {
        $matchThese = ['status_komponen_nilai' => 1];
        // Get Last Komponen Nilai
        $komponen_nilai = KomponenNilai::where($matchThese)->orderBy('id_komponen_nilai', 'desc')->first();
        return $komponen_nilai;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KomponenNilai  $komponenNilai
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Komponen Nilai
        $komponenNilai = KomponenNilai::where('id_komponen_nilai', $id)->first();

        // Return single Komponen Nilai as a Resource
        return new KomponenNilaiResource($komponenNilai);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KomponenNilai  $komponenNilai
     * @return \Illuminate\Http\Response
     */
    public function edit(KomponenNilai $komponenNilai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KomponenNilai  $komponenNilai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KomponenNilai $komponenNilai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KomponenNilai  $komponenNilai
     * @return \Illuminate\Http\Response
     */
    public function destroy(KomponenNilai $komponenNilai)
    {
        //
    }
}
