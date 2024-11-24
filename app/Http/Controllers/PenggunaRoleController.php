<?php

namespace App\Http\Controllers;

use App\Http\Resources\PenggunaRole as ResourcesPenggunaRole;
use App\PenggunaRole;
use Illuminate\Http\Request;

class PenggunaRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_pengguna_role = null)
    {
        $id_pengguna_role = $id_pengguna_role == null ? 1 : $id_pengguna_role;
        $matchThese = ['id_pengguna_role' => $id_pengguna_role];
        // Get Pengguna Role
        $pengguna = PenggunaRole::where($matchThese)->get();
        // Return collection of Pengguna as a resource
        return ResourcesPenggunaRole::collection($pengguna);
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
        $pengguna = $request->isMethod('put') ? PenggunaRole::where('id_pengguna_role', $request->id_pengguna_role)->first() : new PenggunaRole;
        // Insert Pengguna Role
        $pengguna->nama_penguna_role = $request->input('nama_penguna_role');
        $pengguna->status_pengguna_role = $request->input('status_pengguna_role') == null ? 1 : $request->input('status_pengguna_role');
        $success = $pengguna->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PenggunaRole  $penggunaRole
     * @return \Illuminate\Http\Response
     */
    public function show(PenggunaRole $penggunaRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenggunaRole  $penggunaRole
     * @return \Illuminate\Http\Response
     */
    public function edit(PenggunaRole $penggunaRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenggunaRole  $penggunaRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PenggunaRole $penggunaRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PenggunaRole  $penggunaRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenggunaRole $penggunaRole)
    {
        //
    }
}
