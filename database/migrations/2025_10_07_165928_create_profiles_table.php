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
            $table->boolean('payout_confirmations')->default(true);
            $table->boolean('product_launches_tips')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->decimal('height', 8, 2)->nullable(); // in cm
            $table->decimal('weight', 8, 2)->nullable(); // in kg
            $table->string('activity_level')->nullable(); // Sedentary, Moderate, Active, etc.
            $table->string('gym_place')->nullable(); // Gym, Home, Outdoor, etc.
            $table->string('diet')->nullable(); // Omnivore, Vegan, Keto, etc.
            $table->string('allergy')->nullable(); // None, Lactose, Nuts, etc.
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
