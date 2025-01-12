<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topik', function (Blueprint $table) {
            $table->increments('id_topik');
            $table->text('judul_topik');
            $table->text('slug_topik');
            $table->text('latar_belakang');
            $table->text('rumusan_masalah');
            $table->text('tujuan_pembahasan');
            $table->text('ruang_lingkup');
            $table->text('sumber_data');
            $table->text('sistematika_penyajian');
            // $table->text('form_pengajuan_kp');
            $table->text('transkrip_nilai');
            $table->text('dkbs');
            $table->date('tanggal_pengajuan_topik');
            $table->text('form_persetujuan_instansi')->nullable();
            $table->text('lampiran_gambar')->nullable();
            $table->tinyInteger('status_persetujuan_dosen')->nullable();
            $table->tinyInteger('status_topik');
            $table->tinyInteger('is_mbkm');
            $table->integer('id_batch')->unsigned();
            $table->foreign('id_batch')->references('id_batch')->on('batch');
            $table->integer('id_kbk')->unsigned();
            $table->foreign('id_kbk')->references('id_kbk')->on('kbk');
            $table->integer('id_pengaju')->unsigned();
            // $table->foreign('id_pengaju')->references('id_pengguna')->on('pengguna');
            $table->integer('id_pembimbing')->unsigned()->nullable();
            // $table->foreign('id_pembimbing')->references('id_pengguna')->on('pengguna');
            $table->integer('id_pembimbing_lapangan')->unsigned()->nullable();
            // $table->foreign('id_pembimbing_lapangan')->references('id_pengguna')->on('pengguna');
            $table->integer('id_penguji_sidang')->unsigned()->nullable();
            // $table->foreign('id_penguji_sidang')->references('id_pengguna')->on('pengguna');
            $table->integer('id_penguji_sidang_dua')->unsigned()->nullable();
            // $table->foreign('id_penguji_sidang_dua')->references('id_pengguna')->on('pengguna');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topik');
    }
}
