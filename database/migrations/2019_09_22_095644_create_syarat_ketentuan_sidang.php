<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSyaratKetentuanSidang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syarat_ketentuan_sidang', function (Blueprint $table) {
            $table->increments('id_syarat_ketentuan_sidang');
            $table->string('judul_syarat_ketentuan_sidang', 255)->nullable();
            $table->mediumText('deskripsi_syarat_ketentuan_sidang');
            $table->tinyInteger('status_syarat_ketentuan_sidang')->default(1);
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
        Schema::dropIfExists('syarat_ketentuan_sidang');
    }
}
