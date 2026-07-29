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
        Schema::create('onboarding_settings', function (Blueprint $table) {
            $table->id();
            $table->string('badge_text')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('footnote_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_settings');
    }
};
