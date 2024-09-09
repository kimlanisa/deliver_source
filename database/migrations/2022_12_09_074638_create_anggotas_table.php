<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->integer('desa_id');
            $table->string('nama',100);
            $table->string('r_n',10);
            $table->integer('kelas');
            $table->date('tgl_lahir');
            $table->date('tgl_masuk');
            $table->integer('status_pribadi_id');
            $table->string('status_kondisi',100);
            $table->string('alamat',150);
            $table->string('no_hp',15);
            $table->string('keterangan');
            $table->integer('user_id');
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
        Schema::dropIfExists('anggotas');
    }
}
