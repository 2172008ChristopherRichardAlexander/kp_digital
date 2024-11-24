<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sidang', function (Blueprint $table) {
            $table->increments('id_sidang');
            $table->string('ruangan_sidang', 100)->nullable();
            $table->date('tanggal_sidang')->nullable();
            $table->time('waktu_mulai_sidang')->nullable();
            $table->time('waktu_akhir_sidang')->nullable();
            $table->float('nilai_pembimbing', 5, 2)->nullable();
            // $table->float('nilai_pembimbing_non_program', 5, 2)->nullable();
            $table->float('nilai_pembimbing_lapangan', 5, 2)->nullable();
            $table->float('nilai_penguji', 5, 2)->nullable();
            // $table->float('nilai_penguji_non_program', 5, 2)->nullable();
            $table->float('nilai_penguji_dua', 5, 2)->nullable();
            // $table->float('nilai_penguji_dua_non_program', 5, 2)->nullable();
            $table->float('nilai_koor_kp', 5, 2)->nullable();
            $table->text('text_revisi_pembimbing')->nullable();
            $table->string('foto_revisi_pembimbing')->nullable();
            $table->text('text_revisi_penguji')->nullable();
            $table->string('foto_revisi_penguji')->nullable();
            $table->tinyInteger('status_pembimbing_utama')->default(2);
            $table->tinyInteger('status_pembimbing_lapangan')->default(2);
            // $table->tinyInteger('status_revisi')->default(2);
            $table->tinyInteger('status_revisi_pembimbing')->default(2);
            $table->tinyInteger('status_revisi_penguji')->default(2);
            $table->tinyInteger('status_sidang')->default(2);
            $table->integer('id_penguji_sidang')->unsigned()->nullable();
            // $table->foreign('id_penguji_sidang')->references('id_pengguna')->on('pengguna');
            $table->integer('id_penguji_sidang_dua')->unsigned()->nullable();
            // $table->foreign('id_penguji_sidang_dua')->references('id_pengguna')->on('pengguna');
            $table->integer('id_topik')->unsigned();
            $table->foreign('id_topik')->references('id_topik')->on('topik');
            $table->integer('id_batch')->unsigned();
            $table->foreign('id_batch')->references('id_batch')->on('batch');
            $table->integer('id_komponen_nilai')->unsigned();
            $table->foreign('id_komponen_nilai')->references('id_komponen_nilai')->on('komponen_nilai');
            $table->integer('id_jenis_sidang')->unsigned();
            $table->foreign('id_jenis_sidang')->references('id_jenis_sidang')->on('jenis_sidang');
            $table->integer('id_syarat_ketentuan_sidang_mahasiswa')->unsigned()->nullable();
            $table->foreign('id_syarat_ketentuan_sidang_mahasiswa')->references('id_syarat_ketentuan_sidang')->on('syarat_ketentuan_sidang');
            $table->integer('id_syarat_ketentuan_sidang_dosen')->unsigned()->nullable();
            $table->foreign('id_syarat_ketentuan_sidang_dosen')->references('id_syarat_ketentuan_sidang')->on('syarat_ketentuan_sidang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sidang');
    }
}
