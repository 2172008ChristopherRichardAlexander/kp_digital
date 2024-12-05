<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\MBKM;
use App\Http\Requests;
use App\Http\Resources\MBKM as MBKMResource;

class MBKMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mbkm = MBKM::paginate(5);
        // Return collection of MBKM as a resource
        return MBKMResource::collection($mbkm);
    }

    public function getAllByIdSemester($id_semester)
    {
        if ($id_semester!=null) {
            // Ambil data MBKM yang berhubungan dengan semester tertentu
            $kumpulan_mbkm = MBKMResource::collection(MBKM::where('id_semester', $id_semester)->get());
        } else {
            // Jika tidak ada semester yang dipilih, kembalikan semua data MBKM
            $kumpulan_mbkm = MBKMResource::collection(MBKM::paginate(5));
        }
        return $kumpulan_mbkm;
    }

    public function getByIdMBKM($id_mbkm)
    {
        $mbkm = MBKM::where('id_mbkm', $id_mbkm)->first();
        return new MBKMResource($mbkm);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaInstansi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenisMbkm' => 'required|integer',
            'idPengguna'=> 'required|integer',
            'idSemester'=> 'required|integer',
        ]);

        // Create a new MBKM record
        $mbkm = new MBKM;
        $mbkm->nama_instansi = $request->namaInstansi;
        $mbkm->deskripsi = $request->deskripsi;
        $mbkm->id_jenis_mbkm = $request->jenisMbkm;
        $mbkm->id_pengguna = $request->idPengguna;
        $mbkm->id_semester = $request->idSemester;
        $mbkm->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mbkm = MBKM::where('id_pengguna',$id)->first();
        $mbkm->status = $request->input('status');
        $mbkm->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
