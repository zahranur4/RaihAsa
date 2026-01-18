<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('relawan_profiles', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('skill');
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('relawan_profiles', function (Blueprint $table) {
            $table->dropIndex(['kategori']);
            $table->dropColumn('kategori');
        });
    }
};
