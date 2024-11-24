<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKomponenPenilaianSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komponen_penilaian_sidang', function (Blueprint $table) {
            $table->increments('id_komponen_penilaian_sidang');
            $table->integer('id_komponen_penilaian')->unsigned();
            $table->foreign('id_komponen_penilaian')->references('id_komponen_penilaian')->on('komponen_penilaian');
            $table->integer('id_sidang')->unsigned();
            $table->foreign('id_sidang')->references('id_sidang')->on('sidang');
            $table->float('nilai_komponen_penilaian_sidang', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('komponen_penilaian_sidang');
    }
}
