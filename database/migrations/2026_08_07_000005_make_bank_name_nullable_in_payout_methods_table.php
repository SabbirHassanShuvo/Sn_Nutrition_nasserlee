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
        if (Schema::hasTable('payout_methods')) {
            try {
                DB::statement("ALTER TABLE `payout_methods` MODIFY `bank_name` VARCHAR(255) NULL");
            } catch (\Exception $e) {
                // Fallback
            }
        }
        if (Schema::hasTable('payout_requests')) {
            try {
                DB::statement("ALTER TABLE `payout_requests` MODIFY `bank_name` VARCHAR(255) NULL");
            } catch (\Exception $e) {
                // Fallback
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
