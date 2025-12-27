<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('panti_asuhan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis');
            $table->string('nomor_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->unsignedInteger('kapasitas')->nullable();
            $table->string('nomor_legalitas')->nullable();
            $table->date('tanggal_berdiri')->nullable();
            $table->string('nama_penanggung_jawab')->nullable();
            $table->string('posisi_penanggung_jawab')->nullable();
            // NIK is 16 digit numeric
            $table->string('nik', 16);
            // status_verifikasi_legalitas dapat: pending, terverifikasi, ditolak
            $table->enum('status_verifikasi_legalitas', ['pending', 'terverifikasi', 'ditolak'])->default('pending');
            $table->timestamps(); // created_at = account created on
        });

        // Add check constraints when using MySQL where regexp is available
        try {
            if (DB::getDriverName() === 'mysql') {
                // Ensure NIK is 16 digits
                DB::statement("ALTER TABLE panti_asuhan ADD CONSTRAINT chk_panti_nik CHECK (CHAR_LENGTH(nik) = 16 AND nik REGEXP '^[0-9]{16}$')");
                // Basic phone pattern for Indonesia (optional, allow NULL)
                DB::statement("ALTER TABLE panti_asuhan ADD CONSTRAINT chk_panti_nomor_telepon CHECK (nomor_telepon IS NULL OR nomor_telepon REGEXP '^(\\\\+62|62|0)8[0-9]{7,11}$')");
            }
        } catch (\Exception $e) {
            // ignore if platform doesn't support or constraint already exists
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panti_asuhan');
    }
};
