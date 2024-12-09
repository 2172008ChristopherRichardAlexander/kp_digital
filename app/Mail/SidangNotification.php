<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SidangNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $sidang;

    public function __construct($sidang)
    {
        $this->sidang = $sidang;
    }

    public function build()
    {
        return $this->subject('Notifikasi Sidang')
                    ->view('emails.sidang.notification')
                    ->with([
                        'sidang' => $this->sidang,
                    ]);
    }
}
