<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('masterdata_kota', function (Blueprint $table) {
            $table->string('kode_kota')->primary();
            $table->string('kode_provinsi');
            $table->string('nama_kota');

            $table->foreign('kode_provinsi')->references('kode_provinsi')->on('masterdata_provinsi')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masterdata_kota');
    }
};
