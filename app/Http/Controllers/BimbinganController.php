<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Bimbingan;
use App\Http\Requests;
use App\Http\Resources\Bimbingan as BimbinganResource;

class BimbinganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Bimbingan
        $kumpulan_bimbingan = BimbinganResource::collection(Bimbingan::paginate(10));
        // Return collection of Bimbingan as a resource
        return $kumpulan_bimbingan;
    }

    // ! ################# MAHASISWA #################
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
        $bimbingan = $request->isMethod('put') ? Bimbingan::find($request->id_bimbingan) : new Bimbingan;

        $bimbingan->deskripsi_bimbingan = $request->input('deskripsi_bimbingan');
        $bimbingan->tanggal_bimbingan = $request->input('tanggal_bimbingan');
        $bimbingan->status_bimbingan = $request->input('status_bimbingan');
        $bimbingan->id_topik = $request->input('id_topik');
        $bimbingan->id_pembimbing_bimbingan = $request->input('id_pembimbing_bimbingan');
        $bimbingan->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bimbingan  $bimbingan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Bimbingan
        $bimbingan = Bimbingan::where('id_bimbingan', $id)->first();

        // Return single Bimbingan as a Resource
        return new BimbinganResource($bimbingan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bimbingan  $bimbingan
     * @return \Illuminate\Http\Response
     */
    public function edit(Bimbingan $bimbingan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bimbingan  $bimbingan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bimbingan $bimbingan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bimbingan  $bimbingan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bimbingan $bimbingan)
    {
        //
    }

    public function bimbinganTopik(Request $request)
    {
        $id_topik = $request->input('id_topik');
        // List Bimbingan Berdasarkan Topik
        $kumpulan_bimbingan = BimbinganResource::collection(Bimbingan::where('id_topik', $id_topik)->get());
        return $kumpulan_bimbingan;
    }

    public function bimbinganTopikStatus(Request $request)
    {
        $matchThese = ['id_topik' => $request->input('id_topik'), 'status_bimbingan' => $request->input('status_bimbingan')];
        // List Bimbingan Berdasarkan Topik Berdasarkan Status
        $kumpulan_bimbingan = BimbinganResource::collection(Bimbingan::where($matchThese)->get());
        return $kumpulan_bimbingan;
    }

    public function bimbinganKonfirmasi(Request $request)
    {
        $matchThese = ['id_topik' => $request->input('id_topik'), 'id_pembimbing_bimbingan' => $request->input('id_pembimbing_bimbingan'), 'status_bimbingan' => $request->input('status_bimbingan')];
        $kumpulan_bimbingan = BimbinganResource::collection(Bimbingan::where($matchThese)->get());
        return $kumpulan_bimbingan;
    }


    // ! ################# DOSEN #################
    // TODO: Belum Berdasarkan Batch
    public function listBimbingan(Request $request)
    {
        $matchThese = ['bimbingan.id_pembimbing_bimbingan' => $request->input('id_pembimbing_bimbingan'), 'bimbingan.status_bimbingan' => $request->input('status_bimbingan'), 'topik.id_batch' => $request->input('id_batch')];
        $kumpulan_bimbingan = BimbinganResource::collection(Bimbingan::select('bimbingan.*')->join('topik', 'topik.id_topik', '=', 'bimbingan.id_topik')->where($matchThese)->orderBy('bimbingan.id_bimbingan', 'desc')->get());
        return $kumpulan_bimbingan;
    }
    public function konfirmasiBimbingan(Request $request)
    {
        $matchThese = ['id_bimbingan' => $request->input('id_bimbingan')];
        $updateThese = ['status_bimbingan' => $request->input('status_bimbingan')];
        Bimbingan::where($matchThese)->update($updateThese);
    }
    public function konfirmasiListBimbingan(Request $request)
    {
        $response = response();
        try {
            $updateThese = ['status_bimbingan' => $request->input('status_bimbingan')];
            Bimbingan::whereIn('id_bimbingan', $request->input('list_bimbingan'))->update($updateThese);
            $messageSuccess = ["message" => "Konfirmasi Bimbingan Berhasil", "data" => null];
            $response = response($messageSuccess, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            $messageError = ["message" => "Konfirmasi Bimbingan Gagal", "data" => null];
            $response = response($messageError, 400);
        }
        return $response;
    }
}
