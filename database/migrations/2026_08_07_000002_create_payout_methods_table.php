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
        if (!Schema::hasTable('payout_methods')) {
            Schema::create('payout_methods', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->enum('type', ['bank_account', 'debit_card'])->default('bank_account');
                $table->string('bank_name')->nullable();
                $table->string('account_name');
                $table->string('account_number');
                $table->string('routing_number')->nullable();
                $table->string('card_last_four')->nullable();
                $table->string('card_type')->nullable(); // Visa, Mastercard
                $table->string('expiry_date')->nullable();
                $table->string('fees_info')->nullable(); // Free, 0.25% + $0.25
                $table->string('delivery_time')->nullable(); // 1-3 business days, within 24 hours
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_methods');
    }
};
