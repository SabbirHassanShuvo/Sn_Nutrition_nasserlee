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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('payout_confirmations')->default(true)->after('allergy');
            $table->boolean('product_launches_tips')->default(true)->after('payout_confirmations');
            $table->boolean('push_notifications')->default(true)->after('product_launches_tips');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
