<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SidangNotification;
use App\Sidang;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Mengirim email berdasarkan ID Sidang.
     *
     * @param  int  $id_sidang
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request, $id_sidang)
    {
        // Validate incoming request data
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'email', // Ensure all recipients are valid email addresses
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        // Find the 'sidang' by its ID
        $sidang = Sidang::find($id_sidang);

        if (!$sidang) {
            return response()->json(['message' => 'Sidang tidak ditemukan.'], 404);
        }

        // Get the list of recipients from the request
        $recipients = $request->input('recipients');
        $subject = $request->input('subject');
        $message = $request->input('message');

        // Create the email data payload
        $emailData = [
            'sidang' => $sidang,
            'subject' => $subject,
            'message' => $message,
        ];
        return Mail::to($recipients)->send(new SidangNotification($emailData));
        // Send the email using Laravel's Mail facade
        try {
            Mail::to($recipients)->send(new SidangNotification($emailData)); // Assuming you have a 'ScheduleMail' mailable
            return response()->json(['message' => 'Email berhasil dikirim.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengirim email.', 'error' => $e->getMessage()], 500);
        }
    }
}
