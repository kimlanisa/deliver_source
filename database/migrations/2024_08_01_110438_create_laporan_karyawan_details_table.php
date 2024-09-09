<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaporanKaryawanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_karyawan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_karyawan_id')->constrained('laporan_karyawans')->onDelete('cascade');
            $table->longText('pekerjaan');
            $table->string('status');
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('laporan_karyawan_details');
    }
}
