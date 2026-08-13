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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('sendit_delivery_code')->nullable()->after('preferred_delivery_date');
            $table->string('sendit_delivery_status')->nullable()->after('sendit_delivery_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['sendit_delivery_code', 'sendit_delivery_status']);
        });
    }
};
