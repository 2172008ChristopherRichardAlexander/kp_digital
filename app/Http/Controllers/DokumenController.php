<?php

namespace App\Http\Controllers;

use App\Dokumen;
use Illuminate\Http\Request;
use App\Http\Resources\Dokumen as DokumenResource;
class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
     * @param  \App\Dokumen  $dokumen
     * @return \Illuminate\Http\Response
     */
    public function show(Dokumen $dokumen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dokumen  $dokumen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dokumen $dokumen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dokumen  $dokumen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dokumen $dokumen)
    {
        //
    }

    public function uploadTemplate(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenisDokumen' => 'required|exists:jenis_dokumen,id_jenis_dokumen',
            'dokumen' => 'required|file|mimes:docx,pdf,xlsx|max:10240', // Maksimal 10MB
        ]);

        // Dapatkan jenis dokumen yang dipilih
        $jenisDokumen = $request->jenisDokumen;
        $id_pengguna = $request->idPengguna;
        
        $dokumenFolder = '';

        // Tentukan folder penyimpanan berdasarkan jenis dokumen
        if ($jenisDokumen == 1) { // ID untuk laporan
            $dokumenFolder = 'dokumen/template/laporan';
        } elseif ($jenisDokumen == 2) { // ID untuk logbook
            $dokumenFolder = 'dokumen/template/logbook';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jenis dokumen tidak valid.',
            ]);
        }

        // Cek apakah sudah ada dokumen dengan jenis yang sama
        $existingDokumen = Dokumen::where('id_jenis_dokumen', $jenisDokumen)
            ->where('id_pengguna', $id_pengguna) // Atau ID pengguna yang sedang login
            ->first();

        // Jika dokumen lama ada, hapus file yang lama
        if ($existingDokumen) {
            // Hapus file lama dari server
            $existingFilePath = public_path('storage/' . $existingDokumen->file_dokumen);
            if (file_exists($existingFilePath)) {
                unlink($existingFilePath); // Hapus file lama
            }

            // Hapus entri lama di database
            $existingDokumen->delete();
        }

        // Simpan dokumen baru
        $file = $request->file('dokumen');
        $fileName = time() . '-' . $file->getClientOriginalName();
        $filePath = $file->storeAs($dokumenFolder, $fileName, 'public');

        // Simpan data dokumen baru ke tabel dokumen
        $dokumen = new Dokumen();
        $dokumen->file_dokumen = $filePath;
        $dokumen->is_mbkm = $request->is_mbkm ?? 0; // Default jika tidak ada
        $dokumen->id_pengguna = $id_pengguna; // ID pengguna yang sedang login
        $dokumen->id_semester = $request->id_semester ?? 1; // ID Semester yang dipilih, jika tidak ada default 1
        $dokumen->id_jenis_dokumen = $jenisDokumen; // ID Jenis Dokumen yang dipilih
        $dokumen->is_template = 1;
        $dokumen->save();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diunggah!',
            'filePath' => $filePath,
        ]);
    }

    public function listDokumen(Request $request)
    {
        $idSemester = $request->query('id_semester');
        $idJenisDokumen = $request->query('id_jenis_dokumen');
        $isMbkm = $request->query('is_mbkm');

        // Query dokumen berdasarkan parameter yang diberikan
        $query = Dokumen::query(); // Asumsi model Dokumen

        // Filter berdasarkan semester jika ada
        if ($idSemester) {
            $query->where('id_semester', $idSemester);
        }

        // Filter berdasarkan jenis dokumen jika ada
        if ($idJenisDokumen) {
            $query->where('id_jenis_dokumen', $idJenisDokumen);
        }

        // Filter berdasarkan is_mbkm jika ada
        if ($isMbkm !== null) {
            $query->where('is_mbkm', $isMbkm);
        }

        // Ambil data dokumen sesuai filter
        $dokumen = $query->get();

        // Kembalikan response dengan data dokumen
        return response()->json([
            'data' => DokumenResource::collection($dokumen),
        ]);
    }
    public function getListTemplate()
    {
        $dokumenTemplates = Dokumen::where('is_template', true)->get();

        // Mengembalikan response JSON
        return response()->json([
            'data' => $dokumenTemplates
        ]);
    }

}
