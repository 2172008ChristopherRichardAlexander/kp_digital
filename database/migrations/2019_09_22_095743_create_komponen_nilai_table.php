<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKomponenNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komponen_nilai', function (Blueprint $table) {
            $table->increments('id_komponen_nilai');
            $table->string('nama_komponen_nilai');
            $table->tinyInteger('pembimbing');
            $table->tinyInteger('pembimbing_lapangan');
            $table->tinyInteger('penguji');
            $table->tinyInteger('koordinator_kp');
            $table->tinyInteger('status_komponen_nilai')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('komponen_nilai');
    }
}
