<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJabatanPenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_pengguna', function (Blueprint $table) {
            $table->increments('id_jabatan_pengguna');
            $table->integer('id_pengguna')->unsigned();
            // $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna');
            $table->integer('id_jabatan')->unsigned();
            $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jabatan_pengguna');
    }
}
