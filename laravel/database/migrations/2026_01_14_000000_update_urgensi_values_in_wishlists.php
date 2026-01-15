<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, update existing data to map old values to new values
        DB::table('wishlists')->where('urgensi', 'high')->update(['urgensi' => 'mendesak_temp']);
        DB::table('wishlists')->where('urgensi', 'medium')->update(['urgensi' => 'rutin_temp']);
        DB::table('wishlists')->where('urgensi', 'low')->update(['urgensi' => 'pendidikan_temp']);

        // Drop the old enum constraint
        Schema::table('wishlists', function (Blueprint $table) {
            $table->string('urgensi_new')->nullable()->after('urgensi');
        });

        // Copy data to new column
        DB::table('wishlists')->update(['urgensi_new' => DB::raw('urgensi')]);

        // Drop old column
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropColumn('urgensi');
        });

        // Rename new column
        Schema::table('wishlists', function (Blueprint $table) {
            $table->renameColumn('urgensi_new', 'urgensi');
        });

        // Update to final values
        DB::table('wishlists')->where('urgensi', 'mendesak_temp')->update(['urgensi' => 'mendesak']);
        DB::table('wishlists')->where('urgensi', 'rutin_temp')->update(['urgensi' => 'rutin']);
        DB::table('wishlists')->where('urgensi', 'pendidikan_temp')->update(['urgensi' => 'pendidikan']);
    }

    public function down(): void
    {
        // Reverse migration
        DB::table('wishlists')->where('urgensi', 'mendesak')->update(['urgensi' => 'high']);
        DB::table('wishlists')->where('urgensi', 'rutin')->update(['urgensi' => 'medium']);
        DB::table('wishlists')->where('urgensi', 'pendidikan')->update(['urgensi' => 'low']);
        DB::table('wishlists')->where('urgensi', 'kesehatan')->update(['urgensi' => 'low']);

        Schema::table('wishlists', function (Blueprint $table) {
            $table->string('urgensi_old')->nullable()->after('urgensi');
        });

        DB::table('wishlists')->update(['urgensi_old' => DB::raw('urgensi')]);

        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropColumn('urgensi');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->renameColumn('urgensi_old', 'urgensi');
        });
    }
};
