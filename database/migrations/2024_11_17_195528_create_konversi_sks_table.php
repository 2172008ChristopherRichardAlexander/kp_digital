<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKonversiSKSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konversi_sks', function (Blueprint $table) {
            $table->increments('id_matakuliah_detail');
            $table->integer('id_matakuliah')->unsigned();
            $table->foreign('id_matakuliah')->references('id_matakuliah')->on('mata_kuliah');
            $table->integer('id_pengguna')->unsigned();
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna');
            $table->integer('id_jenis_semester')->unsigned();
            $table->foreign('id_jenis_semester')->references('id_jenis_semester')->on('jenis_semester');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('konversi_sks');
    }
}
