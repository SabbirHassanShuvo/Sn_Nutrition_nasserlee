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
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->enum('type', ['global', 'category', 'product', 'health_professional'])->default('global')->after('code');
            $table->foreignId('category_id')->nullable()->after('type')->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
            $table->foreignId('health_professional_id')->nullable()->after('product_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['health_professional_id']);
            $table->dropColumn(['type', 'category_id', 'product_id', 'health_professional_id']);
        });
    }
};
