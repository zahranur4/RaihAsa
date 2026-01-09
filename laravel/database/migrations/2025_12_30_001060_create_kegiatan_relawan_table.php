<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_relawan', function (Blueprint $table) {
            $table->bigIncrements('id_kegiatan');
            $table->unsignedBigInteger('id_panti');
            $table->string('nama_acara');
            $table->date('tgl_acara');
            $table->integer('kuota')->unsigned()->default(0);
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('id_panti')->references('id_panti')->on('panti_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_relawan');
    }
};
