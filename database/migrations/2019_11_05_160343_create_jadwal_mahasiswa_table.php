<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJadwalMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_mahasiswa', function (Blueprint $table) {
            $table->increments('id_jadwal_mahasiswa');
            $table->tinyInteger('hari_jadwal_mahasiswa');
            $table->time('waktu_mulai_jadwal_mahasiswa');
            $table->time('waktu_selesai_jadwal_mahasiswa');
            $table->text('keterangan_jadwal_mahasiswa')->nullable();
            $table->integer('id_topik')->unsigned();
            $table->foreign('id_topik')->references('id_topik')->on('topik');
            $table->integer('id_batch')->unsigned();
            $table->foreign('id_batch')->references('id_batch')->on('batch');
            // $table->integer('id_pengaju')->unsigned();
            // $table->foreign('id_pengaju')->references('id_pengguna')->on('pengguna');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_mahasiswa');
    }
}
