<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJenisSidangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_sidang', function (Blueprint $table) {
            $table->increments('id_jenis_sidang');
            $table->string('nama_jenis_sidang');
            $table->tinyInteger('komponen_nilai_jenis_sidang');
            $table->tinyInteger('status_jenis_sidang')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_sidang');
    }
}
