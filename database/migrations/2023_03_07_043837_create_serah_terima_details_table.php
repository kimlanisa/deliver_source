<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSerahTerimaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serah_terima_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('serah_terima_id');
            $table->string('no_resi');
            $table->integer('expedisi_id');
            $table->timestamps();

            $table->foreign('serah_terima_id')->references('id')->on('serah_terimas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serah_terima_details');
    }
}
