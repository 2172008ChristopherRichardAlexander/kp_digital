<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Komentar;
use App\Http\Requests;
use App\Http\Resources\Komentar as KomentarResource;

class KomentarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_topik)
    {
        $matchThese = ['id_topik' => $id_topik];
        // Get Komentar
        $komentar = Komentar::where($matchThese)->get();
        // Return collection of Komentar as a resource
        return KomentarResource::collection($komentar);
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
        $komentar = new Komentar();
        $komentar->isi_komentar = $request->input('isi_komentar');
        $komentar->tanggal_komentar = date('Y-m-d');
        $komentar->id_topik = $request->input('id_topik');
        $komentar->id_pengguna = $request->input('id_pengguna');
        $komentar->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Komentar
        $komentar = Komentar::where('id_komentar',$id)->first();

        // Return single Komentar as a Resource
        return new KomentarResource($komentar);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function edit(Komentar $komentar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Komentar $komentar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Komentar $komentar)
    {
        //
    }
}
