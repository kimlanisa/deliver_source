<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomMenuValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_menu_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('custom_menu_id')->unsigned();
            $table->foreign('custom_menu_id')->references('id')->on('custom_menus')->onDelete('cascade');
            $table->string('name');
            $table->longText('value');
            $table->bigInteger('created_by')->unsigned();
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
        Schema::dropIfExists('custom_menu_values');
    }
}
