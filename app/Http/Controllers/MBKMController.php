<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function getExistByIdUser($id, $id_semester)
    {
        $exists = MBKM::where('id_pengguna', $id)->where('id_semester',$id_semester)->exists();
        // Mengembalikan response JSON dengan status 'exists'
        return response()->json([
            'exists' => $exists
        ]);
    }

    public function getAllByIdUser($id, $id_semester)
    {
        $exists = MBKM::where('id_pengguna', $id)->where('id_semester',$id_semester)->first();
        // Mengembalikan response JSON dengan status 'exists'
        if ($exists) {
            return response()->json([
                'status' => 'exists',
                'data' => new MBKMResource($exists),
            ], 200);
        } else {
            return response()->json([
                'status' => 'none',
                'message' => 'Tidak ada pendaftaran MBKM yang ditemukan.',
            ], 404);
        }
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
            'nama_instansi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis_mbkm_id' => 'required|integer',
            'idPengguna'=> 'required|integer',
            'id_semester'=> 'required|integer',
        ]);

        // Create a new MBKM record
        $mbkm = new MBKM;
        $mbkm->nama_instansi = $request->nama_instansi;
        $mbkm->deskripsi = $request->deskripsi;
        $mbkm->id_jenis_mbkm = $request->jenis_mbkm_id;
        $mbkm->id_pengguna = $request->idPengguna;
        $mbkm->id_semester = $request->id_semester;
        $mbkm->status = 0;
        $mbkm->save();
        return 'success';
    }

    public function checkExistingMBKM(Request $request)
    {
        $user = Auth::user();
        $semesterId = $request->query('semester_id');

        $existingMBKM = MBKM::where('id_pengguna', $user->id)
                            ->where('id_semester', $semesterId)
                            ->first();

        if ($existingMBKM) {
            return response()->json([
                'exists' => true,
                'status' => $existingMBKM->status
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
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
