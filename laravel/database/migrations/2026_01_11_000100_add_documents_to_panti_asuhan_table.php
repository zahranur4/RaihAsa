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
            // store paths to uploaded documents
            $table->string('doc_akte')->nullable()->after('nik');
            $table->string('doc_sk')->nullable()->after('doc_akte');
            $table->string('doc_npwp')->nullable()->after('doc_sk');
            $table->string('doc_other')->nullable()->after('doc_npwp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panti_asuhan', function (Blueprint $table) {
            $table->dropColumn(['doc_akte', 'doc_sk', 'doc_npwp', 'doc_other']);
        });
    }
};
