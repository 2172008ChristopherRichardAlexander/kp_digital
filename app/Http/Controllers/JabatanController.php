<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Jabatan;
use App\Http\Requests;
use App\Http\Resources\Jabatan as JabatanResource;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Jabatan
        $jabatan = Jabatan::paginate(10);
        // Return collection of Jabatan as a resource
        return JabatanResource::collection($jabatan);
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
        // ! Insert Jabatan
        $jabatan = $request->isMethod('put') ? Jabatan::where('id_jabatan', $request->id_jabatan) : new Jabatan;

        $jabatan->nama_jabatan = $request->input('nama_jabatan');
        $jabatan->status_jabatan = $request->input('status_jabatan');
        $jabatan->id_role = $request->input('id_role');
        $jabatan->id_kbk = $request->input('id_kbk');

        $jabatan->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Jabatan
        $jabatan = Jabatan::where('id_jabatan', $id)->first();

        // Return single Jabatan as a Resource
        return new JabatanResource($jabatan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Jabatan $jabatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jabatan $jabatan)
    {
        //
    }

    public function listJabatanStatus(Request $request)
    {
        $matchThese = ['status_jabatan' => $request->input('status_jabatan')];
        // Get Jabatan
        $jabatan = Jabatan::where($matchThese)->get();
        // Return collection of Jabatan as a resource
        return JabatanResource::collection($jabatan);
    }
}
