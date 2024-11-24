<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Pengguna;
use App\Http\Requests;
use App\Http\Resources\Pengguna as PenggunaResource;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Pengguna
        $pengguna = Pengguna::paginate(10);
        // Return collection of Pengguna as a resource
        return PenggunaResource::collection($pengguna);
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
        $pengguna = $request->isMethod('put') ? Pengguna::where('id_pengguna', $request->id_pengguna)->first() : new Pengguna;
        // Insert Pengguna
        $pengguna->nama_pengguna = $request->input('nama_pengguna');
        $pengguna->kota_lahir = $request->input('kota_lahir');
        $pengguna->tanggal_lahir = $request->input('tanggal_lahir');
        $pengguna->password = $request->input('password');
        $pengguna->kode_pengguna = $request->input('kode_pengguna');
        $pengguna->photo_profile = $request->input('photo_profile');
        $pengguna->nomor_hp = $request->input('nomor_hp');
        $pengguna->email = $request->input('email');
        $pengguna->kontak_lainnya = $request->input('kontak_lainnya');
        $pengguna->linked_in_url = $request->input('linked_in_url');
        $pengguna->id_jurusan = $request->input('id_jurusan');
        $pengguna->id_pengguna_role = $request->input('id_pengguna_role');
        $success = $pengguna->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Pengguna
        $pengguna = Pengguna::where('id_pengguna', $id)->first();

        // Return single Pengguna as a Resource
        return new PenggunaResource($pengguna);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengguna $pengguna)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengguna $pengguna)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // // Get Pengguna
        // $pengguna = Pengguna::where('id_pengguna',$id)->first();

        // if($pengguna->destroy()){
        //     // Return single Pengguna as a Resource
        //     return new PenggunaResource($pengguna);
        // }
    }
}
