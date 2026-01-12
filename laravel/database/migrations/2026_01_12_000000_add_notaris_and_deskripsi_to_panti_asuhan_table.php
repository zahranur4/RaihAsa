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
        Schema::table('panti_asuhan', function (Blueprint $table) {
            if (!Schema::hasColumn('panti_asuhan', 'nama_notaris')) {
                $table->string('nama_notaris')->nullable()->after('tanggal_berdiri');
            }
            if (!Schema::hasColumn('panti_asuhan', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('nama_notaris');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panti_asuhan', function (Blueprint $table) {
            if (Schema::hasColumn('panti_asuhan', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('panti_asuhan', 'nama_notaris')) {
                $table->dropColumn('nama_notaris');
            }
        });
    }
};