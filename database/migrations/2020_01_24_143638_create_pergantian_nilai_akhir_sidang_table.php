<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePergantianNilaiAkhirSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pergantian_nilai_akhir_sidang', function (Blueprint $table) {
            $table->increments('id_pergantian_nilai_akhir_sidang');
            $table->float('nilai_awal', 5, 2);
            $table->float('nilai_ubah', 5, 2)->nullable();
            $table->tinyInteger('status_pembimbing_pergantian_nilai_akhir_sidang')->default(2);
            $table->tinyInteger('status_penguji_pergantian_nilai_akhir_sidang')->default(2);
            $table->tinyInteger('status_penguji_dua_pergantian_nilai_akhir_sidang')->default(2);
            $table->tinyInteger('status_koordinator_kp_pergantian_nilai_akhir_sidang')->default(2);
            $table->integer('id_sidang')->unsigned();
            $table->foreign('id_sidang')->references('id_sidang')->on('sidang');
            $table->integer('id_koordinator_kp')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pergantian_nilai_akhir_sidang');
    }
}
