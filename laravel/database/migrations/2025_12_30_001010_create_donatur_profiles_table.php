<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donatur_profiles', function (Blueprint $table) {
            $table->bigIncrements('id_donatur');
            $table->unsignedBigInteger('id_user');
            $table->string('nama_lengkap');
            $table->string('no_telp');
            $table->text('alamat_jemput')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donatur_profiles');
    }
};
