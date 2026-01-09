<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relawan_profiles', function (Blueprint $table) {
            $table->bigIncrements('id_relawan');
            $table->unsignedBigInteger('id_user');
            $table->string('nama_lengkap');
            $table->string('nik')->nullable();
            $table->text('skill')->nullable();
            $table->enum('status_verif', ['pending','verified','rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relawan_profiles');
    }
};
