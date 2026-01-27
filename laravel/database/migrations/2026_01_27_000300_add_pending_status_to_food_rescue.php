<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the status enum to include 'pending'
        DB::statement("ALTER TABLE `food_rescue` MODIFY `status` ENUM('available','claimed','expired','pending') DEFAULT 'available'");
    }

    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE `food_rescue` MODIFY `status` ENUM('available','claimed','expired') DEFAULT 'available'");
    }
};
