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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // Nama pengguna
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            // Kata sandi disimpan dalam bentuk hashed; pastikan validasi (min 8 karakter, mengandung angka dan huruf) dilakukan di level aplikasi sebelum hashing
            $table->string('kata_sandi');
            // Alamat dan nomor telepon (format Indonesia)
            $table->text('alamat')->nullable();
            $table->string('nomor_telepon', 20)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Optional: add CHECK constraint for nomor_telepon when using MySQL (regex for Indonesian mobile numbers starting with +62, 62 or 0)
        try {
            if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE users ADD CONSTRAINT chk_users_nomor_telepon CHECK (nomor_telepon IS NULL OR nomor_telepon REGEXP '^(\\\\+62|62|0)8[0-9]{7,11}$')");
            }
        } catch (\Exception $e) {
            // Ignore if DB doesn't support or constraint already exists
        }

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
