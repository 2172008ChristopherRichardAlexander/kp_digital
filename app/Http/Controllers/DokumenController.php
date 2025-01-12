<?php

namespace App\Http\Controllers;

use App\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Dokumen as DokumenResource;
use Symfony\Component\HttpFoundation\Response;
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
        // Validasi input
        $request->validate([
            'jenisDokumen' => 'required|exists:jenis_dokumen,id_jenis_dokumen',
            'dokumen' => 'required|file|mimes:pdf,xlsx|max:10240', // Maksimal 10MB
        ]);

        // Dapatkan jenis dokumen yang dipilih
        $jenisDokumen = $request->jenisDokumen;
        $id_pengguna = $request->idPengguna;
        $id_semester = $request->id_semester;
        $dokumenFolder = '';

        // Tentukan folder penyimpanan berdasarkan jenis dokumen
        if ($jenisDokumen == 1) { // ID untuk laporan
            $dokumenFolder = 'dokumen/mahasiswa/laporan';
        } elseif ($jenisDokumen == 2) { // ID untuk logbook
            $dokumenFolder = 'dokumen/mahasiswa/logbook';
        } else if($jenisDokumen == 3) { // ID untuk HistoryKPTA
            $dokumenFolder = 'dokumen/mahasiswa/historyKPTA';
        } else if($jenisDokumen == 4){ // ID untuk FormImas
            $dokumenFolder = 'dokumen/mahasiswa/FormImas';
         }else if($jenisDokumen == 5){ // ID untuk FormBimbingan
            $dokumenFolder = 'dokumen/mahasiswa/FormBimbingan'; 
         }else {
            return response()->json([
                'success' => false,
                'message' => 'Jenis dokumen tidak valid.',
            ]);
        }

        // Cek apakah sudah ada dokumen dengan jenis yang sama
        $existingDokumen = Dokumen::where('id_jenis_dokumen', $jenisDokumen)
            ->where('id_pengguna', $id_pengguna) // Atau ID pengguna yang sedang login
            ->where('id_semester', $id_semester) // Atau ID semester yang aktif
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
        $dokumen->id_pengguna = $id_pengguna; // ID pengguna yang sedang login
        $dokumen->id_semester = $request->id_semester; // ID Semester yang dipilih
        $dokumen->id_jenis_dokumen = $jenisDokumen; // ID Jenis Dokumen yang dipilih
        $dokumen->is_template = 0;
        $dokumen->save();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diunggah!',
            'filePath' => $filePath,
        ]);
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
        } else if($jenisDokumen == 3) { // ID untuk HistoryKPTA
            $dokumenFolder = 'dokumen/template/historyKPTA';
        } else if($jenisDokumen == 4){ // ID untuk FormImas
            $dokumenFolder = 'dokumen/template/FormImas';
         }else if($jenisDokumen == 5){ // ID untuk FormBimbingan
            $dokumenFolder = 'dokumen/template/FormBimbingan';
        }else {
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
        $dokumen->id_pengguna = $id_pengguna; // ID pengguna yang sedang login
        $dokumen->id_semester = $request->id_semester ?? 1; // ID Semester yang dipilih
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
    
        // Mulai dengan query builder
        $query = Dokumen::where('is_template', 0);
    
        // Filter berdasarkan semester jika ada
        if ($idSemester) {
            $query->where('id_semester', $idSemester);
        }
    
        // Filter berdasarkan jenis dokumen jika ada
        if ($idJenisDokumen) {
            $query->where('id_jenis_dokumen', $idJenisDokumen);
        }
    
        // Eksekusi query dan ambil data
        $dokumen = $query->get(); // Mengambil data dokumen sebagai koleksi model Eloquent
       
        // Kembalikan response dengan data dokumen yang sudah diformat dengan resource
        return DokumenResource::collection($dokumen);
    }
    

    public function getListTemplate()
    {
        $dokumenTemplates = Dokumen::where('is_template', true)->get();
        return DokumenResource::collection($dokumenTemplates);
        // Mengembalikan response JSON
    }
    public function download($id)
    {
        // Cari dokumen berdasarkan ID
        $dokumen = Dokumen::where('id_dokumen',$id)->first();
        if (!$dokumen) {
            return response()->json(['message' => 'Dokumen tidak ditemukan.'], 404);
        }

        // Path dokumen
        $filePath = $dokumen->file_dokumen;

        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'File tidak ditemukan di server.'], 404);
        }

        // Ambil nama file asli atau buat nama file
        $fileName = basename($filePath);

        // Return response download file
        return response()->download(storage_path("app/{$filePath}"), $fileName);
    }

    
    public function preview($path)
    {
        // Cek apakah file ada di storage
        if (Storage::disk('public')->exists($path)) {
            // Mendapatkan file dari disk public
            $file = Storage::disk('public')->get($path);
            $mimeType = Storage::disk('public')->mimeType($path);
    
            // Mengembalikan file dalam bentuk response
            return response($file, Response::HTTP_OK)
                ->header('Content-Type', $mimeType);
        }
    
        // Jika file tidak ditemukan
        return response()->json(['message' => 'File tidak ditemukan'], Response::HTTP_NOT_FOUND);
    }
    
}
