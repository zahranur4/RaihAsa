<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->bigIncrements('id_daftar');
            $table->unsignedBigInteger('id_kegiatan');
            $table->unsignedBigInteger('id_relawan');
            $table->dateTime('tgl_daftar')->useCurrent();
            $table->enum('status', ['pending','confirmed','rejected','cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('id_kegiatan')->references('id_kegiatan')->on('kegiatan_relawan')->onDelete('cascade');
            $table->foreign('id_relawan')->references('id_relawan')->on('relawan_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
