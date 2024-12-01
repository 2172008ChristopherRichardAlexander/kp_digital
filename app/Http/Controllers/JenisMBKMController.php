<?php

namespace App\Http\Controllers;

use App\JenisMBKM;
use Illuminate\Http\Request;
use App\Http\Resources\JenisMBKM as JenisMBKMResources;

class JenisMBKMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mbkm = JenisMBKM::all();
        return JenisMBKMResources::collection($mbkm);
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
     * @param  \App\JenisMBKM  $jenisMBKM
     * @return \Illuminate\Http\Response
     */
    public function show(JenisMBKM $jenisMBKM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JenisMBKM  $jenisMBKM
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisMBKM $jenisMBKM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisMBKM  $jenisMBKM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisMBKM $jenisMBKM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisMBKM  $jenisMBKM
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisMBKM $jenisMBKM)
    {
        //
    }
}
