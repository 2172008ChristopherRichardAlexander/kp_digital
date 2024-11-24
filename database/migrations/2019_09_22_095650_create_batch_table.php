<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch', function (Blueprint $table) {
            $table->increments('id_batch');
            $table->string('nama_batch', 100);
            $table->date('tanggal_mulai_batch');
            $table->date('tanggal_selesai_batch')->nullable();
            $table->tinyInteger('jenis_batch');
            $table->tinyInteger('minimal_bimbingan')->default(0);
            $table->tinyInteger('status_batch')->default(1);
            $table->integer('id_jurusan')->unsigned();
            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');
            $table->integer('id_semester')->unsigned();
            $table->foreign('id_semester')->references('id_semester')->on('semester');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch');
    }
}
