<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->increments('id_dokumen');
            $table->string('file_dokumen');
            $table->tinyInteger('is_mbkm');
            $table->tinyInteger('is_template');
            $table->integer('id_pengguna')->unsigned();
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna');
            $table->integer('id_semester')->unsigned();
            $table->foreign('id_semester')->references('id_semester')->on('semester');
            $table->integer('id_jenis_dokumen')->unsigned();
            $table->foreign('id_jenis_dokumen')->references('id_jenis_dokumen')->on('jenis_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Dokumen');
    }
}
