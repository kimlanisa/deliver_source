<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrandChildsCategoriesKatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grand_childs_categories_katalog', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('childs_id');
            $table->string('name');
            $table->string('description');
            $table->string('thumbnail');
            $table->string('photo');
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
        Schema::dropIfExists('grand_childs_categories_katalog');
    }
}