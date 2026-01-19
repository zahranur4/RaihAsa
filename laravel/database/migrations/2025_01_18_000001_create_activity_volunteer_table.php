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
        Schema::create('activity_volunteer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_activity');
            $table->unsignedBigInteger('id_user');
            $table->enum('status', ['registered', 'approved', 'rejected', 'completed', 'cancelled'])->default('registered');
            $table->text('motivation')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('transportation')->nullable(); // motor, mobil, ojek, umum
            $table->timestamps();

            $table->foreign('id_activity')->references('id_activity')->on('volunteer_activities')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['id_activity', 'id_user']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_volunteer');
    }
};
