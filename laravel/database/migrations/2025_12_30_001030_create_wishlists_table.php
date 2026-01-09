<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->bigIncrements('id_wishlist');
            $table->unsignedBigInteger('id_panti');
            $table->string('nama_barang');
            $table->string('kategori')->nullable();
            $table->integer('jumlah')->unsigned();
            $table->enum('urgensi', ['low','medium','high'])->default('medium');
            $table->enum('status', ['open','fulfilled','cancelled'])->default('open');
            $table->timestamps();

            $table->foreign('id_panti')->references('id_panti')->on('panti_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
