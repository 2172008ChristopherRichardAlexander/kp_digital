<?php

namespace App\Console\Commands;

use App\Batch;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AktifasiBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aktifasi:batch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengubah Aktifasi Batch, aktif atau non-aktif batch yang sedang berlangsung';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // ! Non-Aktifkan Batch yang aktif (1) ketika tanggal selesai < tanggal sekarang (jika bertepatan dengan hari ini tidak di Non-Aktifkan)
        Batch::where("status_batch", 1)->whereDate('tanggal_selesai_batch', '<', Carbon::now()->startOfDay())->update(["status_batch" => 0]);
        // ! Aktifkan Batch yang belum aktif (2) ketika tanggal mulai <= tanggal sekarang (jika bertepatan dengan hari ini di Aktifkan)
        Batch::where("status_batch", 2)->whereDate('tanggal_mulai_batch', '<=', Carbon::now()->startOfDay())->update(["status_batch" => 1]);
    }
}
