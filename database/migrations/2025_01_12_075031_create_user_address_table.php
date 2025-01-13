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
        Schema::create('user_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama')->nullable();
            $table->string('nomor_handphone')->nullable();
            $table->string('catatan')->nullable();
            $table->string('kode_provinsi')->nullable();
            $table->string('kode_kota')->nullable();
            $table->string('kode_kecamatan')->nullable();
            $table->string('kode_kelurahan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('alamat_lengkap')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kode_provinsi')->references('kode_provinsi')->on('masterdata_provinsi')->onDelete('cascade');
            $table->foreign('kode_kota')->references('kode_kota')->on('masterdata_kota')->onDelete('cascade');
            $table->foreign('kode_kecamatan')->references('kode_kecamatan')->on('masterdata_kecamatan')->onDelete('cascade');
            $table->foreign('kode_kelurahan')->references('kode_kelurahan')->on('masterdata_kelurahan')->onDelete('cascade');

            # Authorize
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_address');
    }
};
