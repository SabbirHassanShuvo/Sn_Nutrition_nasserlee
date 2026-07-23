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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->decimal('height', 8, 2)->nullable()->after('date_of_birth'); // in cm
            $table->decimal('weight', 8, 2)->nullable()->after('height'); // in kg
            $table->string('activity_level')->nullable()->after('weight'); // Sedentary, Moderate, Active, etc.
            $table->string('gym_place')->nullable()->after('activity_level'); // Gym, Home, Outdoor, etc.
            $table->string('diet')->nullable()->after('gym_place'); // Omnivore, Vegan, Keto, etc.
            $table->string('allergy')->nullable()->after('diet'); // None, Lactose, Nuts, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'date_of_birth',
                'height',
                'weight',
                'activity_level',
                'gym_place',
                'diet',
                'allergy',
            ]);
        });
    }
};
