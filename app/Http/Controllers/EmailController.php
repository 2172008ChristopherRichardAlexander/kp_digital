<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ScheduleMail;
use App\Models\Sidang;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Mengirim email berdasarkan ID Sidang.
     *
     * @param  int  $id_sidang
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail($id_sidang)
    {
        // Validasi bahwa sidang ada
        $sidang = Sidang::with(['topik.pengaju', 'topik.pembimbing', 'topik.pembimbingLapangan', 'topik.pengujiSidang', 'topik.pengujiSidangDua'])->find($id_sidang);

        if (!$sidang) {
            return response()->json(['message' => 'Sidang tidak ditemukan.'], 404);
        }

        // Ambil email penerima dari relasi topik
        $recipients = [];

        // Pengaju
        if ($sidang->topik->pengaju && $sidang->topik->pengaju->email) {
            $recipients[] = $sidang->topik->pengaju->email;
        }

        // Pembimbing
        if ($sidang->topik->pembimbing && $sidang->topik->pembimbing->email) {
            $recipients[] = $sidang->topik->pembimbing->email;
        }

        // Pembimbing Lapangan
        if ($sidang->topik->pembimbingLapangan && $sidang->topik->pembimbingLapangan->email) {
            $recipients[] = $sidang->topik->pembimbingLapangan->email;
        }

        // Penguji Sidang
        if ($sidang->topik->pengujiSidang && $sidang->topik->pengujiSidang->email) {
            $recipients[] = $sidang->topik->pengujiSidang->email;
        }

        // Penguji Sidang Dua
        if ($sidang->topik->pengujiSidangDua && $sidang->topik->pengujiSidangDua->email) {
            $recipients[] = $sidang->topik->pengujiSidangDua->email;
        }

        // Pastikan ada penerima
        if (empty($recipients)) {
            return response()->json(['message' => 'Tidak ada penerima email yang ditemukan.'], 400);
        }

        try {
            // Kirim email ke semua penerima
            Mail::to($recipients)->send(new ScheduleMail($sidang));

            return response()->json(['message' => 'Email berhasil dikirim.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengirim email.', 'error' => $e->getMessage()], 500);
        }
    }
}
