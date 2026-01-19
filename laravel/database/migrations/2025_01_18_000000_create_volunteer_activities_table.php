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
        Schema::create('volunteer_activities', function (Blueprint $table) {
            $table->id('id_activity');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->dateTime('activity_date');
            $table->string('category'); // Edukasi, Kesehatan, Kreatif, Kemanusiaan, Dukungan
            $table->integer('needed_volunteers')->default(1);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();

            $table->index('category');
            $table->index('status');
            $table->index('activity_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_activities');
    }
};
