<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBimbinganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->increments('id_bimbingan');
            $table->text('deskripsi_bimbingan');
            $table->date('tanggal_bimbingan');
            $table->tinyInteger('status_bimbingan')->default(2);
            $table->integer('id_topik')->unsigned();
            $table->foreign('id_topik')->references('id_topik')->on('topik');
            $table->integer('id_pembimbing_bimbingan')->unsigned();
            // $table->foreign('id_pembimbing_bimbingan')->references('id_pengguna')->on('pengguna');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bimbingan');
    }
}
