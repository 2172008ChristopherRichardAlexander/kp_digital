<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKomponenPenilaianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komponen_penilaian', function (Blueprint $table) {
            $table->increments('id_komponen_penilaian');
            $table->string('judul_komponen_penilaian', 100);
            $table->text('deskripsi_komponen_penilaian')->nullable();
            $table->float('bobot_komponen_penilaian', 4, 3);
            $table->tinyInteger('status_komponen_penilaian')->default(1);
            $table->integer('id_role')->unsigned();
            $table->foreign('id_role')->references('id_role')->on('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('komponen_penilaian');
    }
}
