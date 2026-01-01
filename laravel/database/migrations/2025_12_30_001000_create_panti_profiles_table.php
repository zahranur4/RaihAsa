<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panti_profiles', function (Blueprint $table) {
            $table->bigIncrements('id_panti');
            $table->unsignedBigInteger('id_user');
            $table->string('nama_panti');
            $table->text('alamat_lengkap');
            $table->string('kota');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('no_sk')->nullable();
            $table->enum('status_verif', ['pending','verified','rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panti_profiles');
    }
};
