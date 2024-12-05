<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\MBKM;
use App\KonversiSKS;
use App\Http\Requests;
use App\Http\Resources\KonversiSKS as KonversiSKSResource;

class KonversiSKSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getAllByIdPengguna($id_pengguna)
    {
        if ($id_pengguna != null) {
            // Ambil data MBKM yang berhubungan dengan semester tertentu
            $kumpulan_konversi = KonversiSKSResource::collection(KonversiSKS::where('id_pengguna', $id_pengguna)->get());
        }
        return $kumpulan_konversi;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->all() as $data) {
            // Jika $data adalah array
            if (is_array($data)) {
                $id_matakuliah = $data['id_matakuliah'];
                $id_pengguna = $data['id_pengguna'];
                $id_semester = $data['id_semester'];
            } else {
                // Jika $data adalah objek, gunakan input()
                $id_matakuliah = $data->input('id_matakuliah');
                $id_pengguna = $data->input('id_pengguna');
                $id_semester = $data->input('id_semester');
            }
            // Tentukan apakah ini update atau create
            $konversiSKS = $request->isMethod('put') && $request->id_matakuliah_detail ? KonversiSKS::find($request->id_matakuliah_detail) : new KonversiSKS;
            $konversiSKS->id_matakuliah = $id_matakuliah;
            $konversiSKS->id_pengguna = $id_pengguna;
            $konversiSKS->id_semester = $id_semester;
            $konversiSKS->save();
        }
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
    public function update(Request $request, $id_pengguna)
    {
         // Validasi input
        $request->validate([
            'selectedCourses' => 'required|array',
            'selectedCourses.*.id_matakuliah' => 'required|exists:mata_kuliah,id_matakuliah',
        ]);

        // Ambil semua kursus yang dipilih
        $selectedCourses = $request->input('selectedCourses');

        // Hapus konversi SKS yang tidak dipilih lagi
        // Pertama, ambil semua konversi yang ada untuk pengguna ini
        $existingCourses = KonversiSKS::where('id_pengguna', $id_pengguna)->get();

        // Cari kursus yang tidak dipilih
        $deselectedCourses = $existingCourses->filter(function ($course) use ($selectedCourses) {
            return !collect($selectedCourses)->contains('id_matakuliah', $course->id_matakuliah);
        });

        // Hapus kursus yang tidak dipilih
        foreach ($deselectedCourses as $course) {
            $course->delete();
        }

        // Simpan kursus yang dipilih atau update konversi yang ada
        foreach ($selectedCourses as $course) {
            // Cek apakah kursus sudah ada dalam konversi untuk pengguna ini
            $existingCourse = KonversiSKS::where('id_pengguna', $id_pengguna)
                                          ->where('id_matakuliah', $course['id_matakuliah'])
                                          ->first();

            if (!$existingCourse) {
                // Jika belum ada, simpan konversi SKS baru
                KonversiSKS::create([
                    'id_pengguna' => $id_pengguna,
                    'id_matakuliah' => $course['id_matakuliah'],
                    'id_semester' => $course['id_semester'],
                ]);
            }
        }
        return response()->json([
            'message' => 'Konversi SKS berhasil diperbarui dan status MBKM telah diperbarui!',
        ]);
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
    public function deleteKonversiSKS($id_pengguna, $id_matakuliah)
    {
        // Mencari konversi SKS berdasarkan id_pengguna dan id_matakuliah
        $konversiSKS = KonversiSKS::where('id_pengguna', $id_pengguna)
                                  ->where('id_matakuliah', $id_matakuliah)
                                  ->first();

        if (!$konversiSKS) {
            return response()->json(['message' => 'Data konversi SKS tidak ditemukan.'], 404);
        }

        // Menghapus data konversi SKS yang ditemukan
        $konversiSKS->delete();

        return response()->json(['message' => 'Konversi SKS berhasil dihapus.'], 200);
    }
}
