<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkToPhotoKatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photo_katalog', function (Blueprint $table) {
            $table->string('variasi')->after('name')->nullable();
            $table->string('link_url')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photo_katalog', function (Blueprint $table) {
            //
        });
    }
}