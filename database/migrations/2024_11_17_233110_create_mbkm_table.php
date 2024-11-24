<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMBKMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mbkm', function (Blueprint $table) {
            $table->increments('id_mbkm');
            $table->string('nama_instansi');
            $table->string('deskripsi_mbkm');
            $table->tinyInteger('status')->default(1);
            $table->integer('id_jenis_mbkm')->unsigned();
            $table->foreign('id_jenis_mbkm')->references('id_jenis_mbkm')->on('jenis_mbkm');
            $table->integer('id_pengguna')->unsigned();
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna');
            $table->integer('id_semester')->unsigned();
            $table->foreign('id_semester')->references('id_semester')->on('semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mbkm');
    }
}
