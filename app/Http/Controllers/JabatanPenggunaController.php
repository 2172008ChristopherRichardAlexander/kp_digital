<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\JabatanPengguna;
// use App\Http\Requests;
use App\Http\Resources\JabatanPengguna as JabatanPenggunaResource;

class JabatanPenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Jabatan Pengguna
        $jabatanPengguna = JabatanPengguna::paginate(10);
        // Return collection of Jabatan Pengguna as a resource
        return JabatanPenggunaResource::collection($jabatanPengguna);
    }

    public function listJabatanPengguna(Request $request)
    {

        $matchTheseOne = ['id_pengguna' => $request->input('id_pengguna')];
        $jabatanPengguna = JabatanPengguna::where($matchTheseOne)->get();
        // Return collection of Jabatan Pengguna as a resource
        return JabatanPenggunaResource::collection($jabatanPengguna);
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
        $jabatan = $request->isMethod('put') ? JabatanPengguna::where('id_jabatan_pengguna', $request->id_jabatan_pengguna) : new JabatanPengguna;

        $jabatan->id_pengguna = $request->input('id_pengguna');
        $jabatan->id_jabatan = $request->input('id_jabatan');

        $jabatan->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JabatanPengguna  $jabatanPengguna
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Jabatan Pengguna
        $jabatanPengguna = JabatanPengguna::where('id_jabatan_pengguna', $id)->first();

        // Return single Jabatan Pengguna as a Resource
        return new JabatanPenggunaResource($jabatanPengguna);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JabatanPengguna  $jabatanPengguna
     * @return \Illuminate\Http\Response
     */
    public function edit(JabatanPengguna $jabatanPengguna)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JabatanPengguna  $jabatanPengguna
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JabatanPengguna $jabatanPengguna)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JabatanPengguna  $jabatanPengguna
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $jabatanPengguna = JabatanPengguna::where('id_jabatan_pengguna', $request->input('id_jabatan_pengguna'))->first();
        $jabatanPengguna->delete();
    }


    public function dosen()
    {
        // Get Jabatan Pengguna Dosen
        $jabatanPengguna = JabatanPengguna::where('id_jabatan', '!=', 1)->where('id_jabatan', '!=', 2)->where('id_jabatan', '!=', 12)->get();

        // Return single Pengguna as a Resource
        return JabatanPenggunaResource::collection($jabatanPengguna);
    }

    public function mahasiswa()
    {
        // Get Jabatan Pengguna Mahasiswa
        $jabatanPengguna = JabatanPengguna::where('id_jabatan', 12)
            ->join('pengguna', 'pengguna.id_pengguna', 'jabatan_pengguna.id_pengguna')
            ->get();

        // Return single Pengguna as a Resource
        return JabatanPenggunaResource::collection($jabatanPengguna);
    }

    public function firstLogin(Request $request)
    {
        $jabatanPengguna = JabatanPengguna::firstOrCreate(['id_pengguna' => $request->id_pengguna], ['id_jabatan' => $request->id_jabatan]);
    }

    public function deleteJabatanPengguna(Request $request)
    {
        $jabatanPengguna = JabatanPengguna::where('id_jabatan_pengguna', $request->input('id_jabatan_pengguna'))->first();
        $jabatanPengguna->delete();
    }

    public function getJabatanPenggunaKbk($id_kbk){
        $matchThese = ['jabatan.id_kbk' => $id_kbk];
        return JabatanPenggunaResource::collection(JabatanPengguna::select('jabatan_pengguna.*')->join('jabatan', 'jabatan.id_jabatan', '=', 'jabatan_pengguna.id_jabatan')->where($matchThese)->get());
    }
}
