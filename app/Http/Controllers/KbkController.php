<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Kbk;
use App\Http\Requests;
use App\Http\Resources\Kbk as KbkResource;

class KbkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Kbk
        $kbk = Kbk::where('id_kbk', '<', 5)->get();
        // Return collection of Kbk as a resource
        return KbkResource::collection($kbk);
    }

    public function aktif()
    {
        // Get Kbk aktif
        $kbk = Kbk::where('status_kbk', 1)->get();
        // Return collection of Kbk as a resource
        return KbkResource::collection($kbk);
    }

    public function getKbkNama(Request $request)
    {
        // Get Kbk aktif
        $kbk = Kbk::where('nama_kbk', $request->input('nama_kbk'))->get();
        // Return collection of Kbk as a resource
        return KbkResource::collection($kbk);
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
        $kbk = $request->isMethod('put') ? Kbk::find($request->id_kbk) : new Kbk();

        $kbk->nama_kbk = $request->input('nama_kbk');
        $kbk->status_kbk = $request->input('status_kbk');
        $kbk->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kbk  $kbk
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Kbk
        $kbk = Kbk::where('id_kbk', $id)->first();

        // Return single Kbk as a Resource
        return new KbkResource($kbk);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kbk  $kbk
     * @return \Illuminate\Http\Response
     */
    public function edit(Kbk $kbk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kbk  $kbk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kbk $kbk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kbk  $kbk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kbk $kbk)
    {
        //
    }
}
