<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMataKuliahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->increments('id_matakuliah')->unsigned();
            $table->string('nama_matakul');
            $table->integer('jumlah_sks')->unsigned();
            $table->integer('id_jenis_semester')->unsigned();
            $table->foreign('id_jenis_semester')->references('id_jenis_semester')->on('jenis_semester');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('mata_kuliah');
    }
}
