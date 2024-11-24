<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->integer('id_pengguna')->unsigned();
            $table->primary('id_pengguna');
            $table->string('nama_pengguna')->nullable();
            $table->string('kota_lahir')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('password')->nullable();
            $table->string('kode_pengguna')->nullable();
            $table->string('photo_profile')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('kontak_lainnya')->nullable();
            $table->integer('id_pengguna_role')->unsigned()->nullable();
            $table->foreign('id_pengguna_role')->references('id_pengguna_role')->on('pengguna_role');
            $table->integer('id_jurusan')->unsigned()->nullable();
            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengguna');
    }
}
