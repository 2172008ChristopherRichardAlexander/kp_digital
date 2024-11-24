<?php

namespace App\Http\Controllers;

use App\JadwalMahasiswa;
use App\Http\Resources\JadwalMahasiswa as JadwalMahasiswaResource;
use Illuminate\Http\Request;

class JadwalMahasiswaController extends Controller
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
        $jadwal_mahasiswa = $request->isMethod('put') ? JadwalMahasiswa::find($request->id_jadwal_mahasiswa) : new JadwalMahasiswa;

        $jadwal_mahasiswa->hari_jadwal_mahasiswa = $request->input('hari_jadwal_mahasiswa');
        $jadwal_mahasiswa->waktu_mulai_jadwal_mahasiswa = $request->input('waktu_mulai_jadwal_mahasiswa');
        $jadwal_mahasiswa->waktu_selesai_jadwal_mahasiswa = $request->input('waktu_selesai_jadwal_mahasiswa');
        $jadwal_mahasiswa->keterangan_jadwal_mahasiswa = $request->input('keterangan_jadwal_mahasiswa');
        $jadwal_mahasiswa->id_topik = $request->input('id_topik');
        $jadwal_mahasiswa->id_batch = $request->input('id_batch');

        $jadwal_mahasiswa->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JadwalMahasiswa  $jadwalMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(JadwalMahasiswa $jadwalMahasiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JadwalMahasiswa  $jadwalMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(JadwalMahasiswa $jadwalMahasiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JadwalMahasiswa  $jadwalMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JadwalMahasiswa $jadwalMahasiswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JadwalMahasiswa  $jadwalMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(JadwalMahasiswa $jadwalMahasiswa)
    {
        //
    }


    // ! ################# Mahasiwa #################
    public function listJadwalMahasiswa(Request $request)
    {
        $matchThese = ['id_topik' => $request->input('id_topik'), 'id_batch' => $request->input('id_batch')];
        return JadwalMahasiswaResource::collection(JadwalMahasiswa::where($matchThese)->get());
    }

    public function getJadwalMahasiswa($id_jadwal_mahasiswa)
    {
        $matchThese = ['id_jadwal_mahasiswa' => $id_jadwal_mahasiswa];

        return new JadwalMahasiswaResource(JadwalMahasiswa::where($matchThese)->first());
    }

    public function updateJadwalMahasiswa(Request $request)
    {
        $updateThese = [
            'hari_jadwal_mahasiswa' => $request->input('hari_jadwal_mahasiswa'),
            'waktu_mulai_jadwal_mahasiswa' => $request->input('waktu_mulai_jadwal_mahasiswa'),
            'waktu_selesai_jadwal_mahasiswa' => $request->input('waktu_selesai_jadwal_mahasiswa'),
            'keterangan_jadwal_mahasiswa' => $request->input('keterangan_jadwal_mahasiswa'),
        ];
        $matchThese = ['id_jadwal_mahasiswa' => $request->input('id_jadwal_mahasiswa')];
        JadwalMahasiswa::where($matchThese)->update($updateThese);
    }

    public function deleteJadwalMahasiswa(Request $request)
    {
        $jadwal = JadwalMahasiswa::where('id_jadwal_mahasiswa', $request->input('id_jadwal_mahasiswa'))->first();
        $jadwal->delete();
    }

    // ! ################# Koordinator KP #################
    public function listJadwalBatchTopik(Request $request)
    {
        $matchThese = ['id_batch' => $request->input('id_batch'), 'id_topik' => $request->input('id_topik')];
        return JadwalMahasiswaResource::collection(JadwalMahasiswa::where($matchThese)->get());
    }
}
