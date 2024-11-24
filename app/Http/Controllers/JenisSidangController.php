<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\JenisSidang;
use App\Http\Requests;
use App\Http\Resources\JenisSidang as JenisSidangResource;

class JenisSidangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Jenis Sidang
        $jenisSidang = JenisSidang::paginate(10);
        // Return collection of Jenis Sidang as a resource
        return JenisSidangResource::collection($jenisSidang);
    }

    public function newest()
    {
        $matchThese = ['status_jenis_sidang' => 1];
        // Get Last Jenis Sidang
        $jenis_sidang = JenisSidang::where($matchThese)->orderBy('id_jenis_sidang', 'desc')->first();
        return $jenis_sidang;
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
     * @param  \App\JenisSidang  $jenisSidang
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Jenis Sidang
        $jenisSidang = JenisSidang::where('id_jenis_sidang', $id)->first();

        // Return single Jenis Sidang as a Resource
        return new JenisSidangResource($jenisSidang);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JenisSidang  $jenisSidang
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisSidang $jenisSidang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisSidang  $jenisSidang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisSidang $jenisSidang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisSidang  $jenisSidang
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisSidang $jenisSidang)
    {
        //
    }
}
