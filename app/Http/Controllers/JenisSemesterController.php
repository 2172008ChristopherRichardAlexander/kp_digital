<?php

namespace App\Http\Controllers;

use App\JenisSemester;
use Illuminate\Http\Request;
use App\Http\Resources\JenisSemester as JenisSemesterResources;
class JenisSemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenissemester = JenisSemester::all();
        return JenisSemesterResources::collection($jenissemester);
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
     * @param  \App\JenisSemester  $jenisSemester
     * @return \Illuminate\Http\Response
     */
    public function show(JenisSemester $jenisSemester)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JenisSemester  $jenisSemester
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisSemester $jenisSemester)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisSemester  $jenisSemester
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisSemester $jenisSemester)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisSemester  $jenisSemester
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisSemester $jenisSemester)
    {
        //
    }
}
