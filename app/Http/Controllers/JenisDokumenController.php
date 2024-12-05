<?php

namespace App\Http\Controllers;

use App\JenisDokumen;
use Illuminate\Http\Request;
use App\Http\Resources\JenisDokumen as JenisDokumenResources;

class JenisDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenisDokumen = JenisDokumen::all();
        return JenisDokumenResources::collection($jenisDokumen);
        

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
     * @param  \App\JenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function show(JenisDokumen $jenisDokumen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisDokumen $jenisDokumen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisDokumen $jenisDokumen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisDokumen  $jenisDokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisDokumen $jenisDokumen)
    {
        //
    }
}
