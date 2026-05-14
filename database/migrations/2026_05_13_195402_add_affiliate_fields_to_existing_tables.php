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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('commission_percent', 5, 2)->default(0)->after('price');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('affiliate_link_id')->nullable()->constrained('affiliate_links')->nullOnDelete();
            $table->decimal('commission_amount', 10, 2)->default(0)->after('total');
        });

        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->decimal('lifetime_earnings', 12, 2)->default(0);
            $table->decimal('pending_payout', 12, 2)->default(0);
            $table->decimal('last_paid_amount', 12, 2)->default(0);
            $table->enum('current_tier', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('commission_percent');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('affiliate_link_id');
            $table->dropColumn('commission_amount');
        });

        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->dropColumn(['lifetime_earnings', 'pending_payout', 'last_paid_amount', 'current_tier']);
        });
    }
};
