<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasi_barang', function (Blueprint $table) {
            $table->bigIncrements('id_donasi');
            $table->unsignedBigInteger('id_donatur');
            $table->string('nama_barang');
            $table->string('kategori')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['pending','accepted','delivered','cancelled'])->default('pending');
            $table->unsignedBigInteger('id_panti')->nullable();
            $table->timestamps();

            $table->foreign('id_donatur')->references('id_donatur')->on('donatur_profiles')->onDelete('cascade');
            $table->foreign('id_panti')->references('id_panti')->on('panti_profiles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasi_barang');
    }
};
