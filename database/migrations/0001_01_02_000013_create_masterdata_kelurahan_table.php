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
        Schema::create('masterdata_kelurahan', function (Blueprint $table) {
            $table->string('kode_kelurahan')->primary();
            $table->string('kode_kecamatan');
            $table->string('nama_kelurahan');

            $table->foreign('kode_kecamatan')->references('kode_kecamatan')->on('masterdata_kecamatan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masterdata_kelurahan');
    }
};
