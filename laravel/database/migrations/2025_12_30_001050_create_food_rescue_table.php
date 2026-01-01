<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_rescue', function (Blueprint $table) {
            $table->bigIncrements('id_food');
            $table->unsignedBigInteger('id_donatur');
            $table->string('nama_makanan');
            $table->integer('porsi')->unsigned();
            $table->dateTime('waktu_dibuat');
            $table->dateTime('waktu_expired')->nullable();
            $table->enum('status', ['available','claimed','expired'])->default('available');
            $table->unsignedBigInteger('id_claimer')->nullable();
            $table->timestamps();

            $table->foreign('id_donatur')->references('id_donatur')->on('donatur_profiles')->onDelete('cascade');
            $table->foreign('id_claimer')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_rescue');
    }
};
