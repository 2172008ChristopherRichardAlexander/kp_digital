<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJabatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->increments('id_jabatan');
            $table->string('nama_jabatan',100);
            $table->tinyInteger('status_jabatan')->default(1);
            $table->integer('id_role')->unsigned();
            $table->foreign('id_role')->references('id_role')->on('role');
            $table->integer('id_kbk')->unsigned();
            $table->foreign('id_kbk')->references('id_kbk')->on('kbk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jabatan');
    }
}
