<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomMenuLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_menu_labels', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('custom_menu_id')->unsigned();
            $table->string('label');
            $table->string('name');
            $table->string('type_input');
            $table->boolean('show_data')->default(false);
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
        Schema::dropIfExists('custom_menu_labels');
    }
}
