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
        if (!Schema::hasTable('affiliate_tier_settings')) {
            Schema::create('affiliate_tier_settings', function (Blueprint $table) {
                $table->id();
                $table->string('tier_key')->unique(); // bronze, silver, gold, platinum
                $table->string('tier_name'); // Bronze, Silver, Gold, Platinum
                $table->decimal('min_earnings', 10, 2)->default(0);
                $table->decimal('bonus_percent', 5, 2)->default(0);
                $table->string('badge_color')->nullable();
                $table->timestamps();
            });

            // Seed default tiers
            DB::table('affiliate_tier_settings')->insert([
                ['tier_key' => 'bronze', 'tier_name' => 'Bronze', 'min_earnings' => 0.00, 'bonus_percent' => 0.00, 'badge_color' => '#cd7f32', 'created_at' => now(), 'updated_at' => now()],
                ['tier_key' => 'silver', 'tier_name' => 'Silver', 'min_earnings' => 1500.00, 'bonus_percent' => 2.00, 'badge_color' => '#c0c0c0', 'created_at' => now(), 'updated_at' => now()],
                ['tier_key' => 'gold', 'tier_name' => 'Gold', 'min_earnings' => 3000.00, 'bonus_percent' => 5.00, 'badge_color' => '#ffd700', 'created_at' => now(), 'updated_at' => now()],
                ['tier_key' => 'platinum', 'tier_name' => 'Platinum', 'min_earnings' => 6000.00, 'bonus_percent' => 8.00, 'badge_color' => '#e5e4e2', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_tier_settings');
    }
};
