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
        Schema::create('wishlist_pledges', function (Blueprint $table) {
            $table->id('id_pledge');
            $table->unsignedBigInteger('id_wishlist');
            $table->unsignedBigInteger('id_donatur');
            $table->string('item_offered');
            $table->integer('quantity_offered');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('id_wishlist')->references('id_wishlist')->on('wishlists')->onDelete('cascade');
            $table->foreign('id_donatur')->references('id_donatur')->on('donatur_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_pledges');
    }
};
