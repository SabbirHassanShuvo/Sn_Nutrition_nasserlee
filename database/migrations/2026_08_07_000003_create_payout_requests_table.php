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
        if (!Schema::hasTable('payout_requests')) {
            Schema::create('payout_requests', function (Blueprint $table) {
                $table->id();
                $table->string('payout_number')->unique();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->enum('type', ['bank_account', 'debit_card'])->default('bank_account');
                $table->decimal('amount', 10, 2);
                $table->string('bank_name')->nullable();
                $table->string('account_name');
                $table->string('account_number');
                $table->string('routing_number')->nullable();
                $table->string('card_last_four')->nullable();
                $table->string('card_type')->nullable();
                $table->enum('status', ['pending', 'paid', 'rejected'])->default('pending');
                $table->string('receipt_image')->nullable();
                $table->text('admin_notes')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
