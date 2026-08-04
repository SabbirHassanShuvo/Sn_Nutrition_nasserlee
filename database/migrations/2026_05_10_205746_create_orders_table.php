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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('bank_transfer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('affiliate_link_id')->nullable()->constrained('affiliate_links')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'processing', 'shipping', 'delivered', 'cancelled'])->default('pending');
            // Contact & Shipping
            $table->string('phone')->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            
            // Delivery & Payment
            $table->string('delivery_method')->nullable(); // standard, express
            $table->string('payment_method')->nullable(); // cod, bank_transfer
            $table->date('preferred_delivery_date')->nullable();
            
            $table->timestamps();
        });

        Schema::table('bank_transfers', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_transfers', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::dropIfExists('orders');
    }
};
