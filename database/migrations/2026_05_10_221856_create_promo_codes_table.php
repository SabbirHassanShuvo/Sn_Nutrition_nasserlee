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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount_percent', 5, 2);
            $table->date('expiry_date')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Add promo_code and discount to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->string('applied_promo_code')->nullable()->after('payment_method');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('applied_promo_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['applied_promo_code', 'discount_amount']);
        });
    }
};
