<?php

namespace App\Http\Controllers;

use App\Http\Resources\Semester as SemesterResource;
use App\Semester;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get List Semester
        return SemesterResource::collection(Semester::orderBy('id_semester', 'desc')->get());
    }

    public function aktif()
    {
        return Semester::where('status_semester', 1)->first();
    }

    // ! LIST Semester
    // ? Status,
    // * Mendapatkan List Semester Berdasarkan status yang diberikan
    public function listSemester(Request $request)
    {
        $status = explode(",", $request->input('status_semester'));
        $semester = Semester::whereIn('status_semester', $status)->orderBy('id_semester', 'desc')->get();
        return SemesterResource::collection($semester);
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
        $semester = $request->isMethod('put') ? Semester::find($request->id_semester) : new Semester;

        $semester->nama_semester = $request->input('nama_semester');
        $semester->tanggal_mulai_semester = $request->input('tanggal_mulai_semester');
        $semester->tanggal_selesai_semester = $request->input('tanggal_selesai_semester');
        $semester->status_semester = $request->input('status_semester');

        $semester->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function show(Semester $semester)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function edit(Semester $semester)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Semester $semester)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function destroy(Semester $semester)
    {
        //
    }






    public function aktifasiSemester()
    {
        // ! Non-Aktifkan Semester yang aktif (1) ketika tanggal selesai < tanggal sekarang (jika bertepatan dengan hari ini tidak di Non-Aktifkan)
        Semester::where("status_semester", 1)->whereDate('tanggal_selesai_semester', '<', Carbon::now()->startOfDay())->update(["status_semester" => 0]);
        // ! Aktifkan Semester yang belum aktif (2) ketika tanggal mulai <= tanggal sekarang (jika bertepatan dengan hari ini di Aktifkan)
        Semester::where("status_semester", 2)->whereDate('tanggal_mulai_semester', '<=', Carbon::now()->startOfDay())->update(["status_semester" => 1]);
        return ['message' => 'Semester berhasil di check'];
    }
}
