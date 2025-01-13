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
        Schema::create('masterdata_kecamatan', function (Blueprint $table) {
            $table->string('kode_kecamatan')->primary();
            $table->string('kode_kota');
            $table->string('nama_kecamatan');

            $table->foreign('kode_kota')->references('kode_kota')->on('masterdata_kota')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masterdata_kecamatan');
    }
};
