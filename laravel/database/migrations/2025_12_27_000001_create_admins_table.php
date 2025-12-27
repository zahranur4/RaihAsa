<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            // Kata sandi disimpan ter-hash
            $table->string('kata_sandi');
            $table->timestamps();
        });

        // Insert default admin dengan password yang diminta (hashed)
        try {
            DB::table('admins')->insert([
                'username' => 'admin',
                'kata_sandi' => Hash::make('@dM1nR4ih4sa'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // ignore if insertion fails (e.g., already exists)
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
