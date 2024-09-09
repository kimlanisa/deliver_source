<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogActivitisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('log_activitis', function (Blueprint $table) {
        //     $table->id();
        //     $table->bigInteger('users_id');
        //     $table->bigInteger('serah_terima_id');
        //     $table->text('serah_terima_detail_resi');
        //     $table->text('keterangan');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('log_activitis');
    }
}
