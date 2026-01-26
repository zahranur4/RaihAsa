<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food_rescue', function (Blueprint $table) {
            if (!Schema::hasColumn('food_rescue', 'foto')) {
                $table->string('foto')->nullable()->after('porsi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('food_rescue', function (Blueprint $table) {
            if (Schema::hasColumn('food_rescue', 'foto')) {
                $table->dropColumn('foto');
            }
        });
    }
};
