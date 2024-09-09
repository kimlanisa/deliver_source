<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoKatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_katalog', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parents_id');
            $table->unsignedBigInteger('childs_id');
            $table->unsignedBigInteger('grand_childs_id');
            $table->string('thumbnail');
            $table->string('name');
            $table->string('description');
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
        Schema::dropIfExists('photo_katalog');
    }
}