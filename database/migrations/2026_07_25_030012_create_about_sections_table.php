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
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();

            // Story Section (Top Section)
            $table->string('story_badge')->nullable(); // e.g. Our Story
            $table->string('story_title')->nullable(); // e.g. Nutrition you can trust
            $table->string('story_title_highlight')->nullable(); // e.g. trust (word wrapped in green italic)
            $table->text('story_description')->nullable();
            $table->string('story_image')->nullable();

            // Mission Section
            $table->string('mission_title')->nullable(); // e.g. Our Mission
            $table->string('mission_title_highlight')->nullable(); // e.g. Mission
            $table->text('mission_description')->nullable();

            // Mission Statistics/Stats (4 items)
            $table->string('stat1_value')->nullable(); // e.g. 4,200+
            $table->string('stat1_label')->nullable(); // e.g. Health pros
            $table->string('stat2_value')->nullable(); // e.g. $8.4M
            $table->string('stat2_label')->nullable(); // e.g. Paid out in 2024
            $table->string('stat3_value')->nullable(); // e.g. 98.6%
            $table->string('stat3_label')->nullable(); // e.g. On-time payouts
            $table->string('stat4_value')->nullable(); // e.g. 25%
            $table->string('stat4_label')->nullable(); // e.g. Top commission

            // Product Standards Section
            $table->string('standards_title')->nullable(); // e.g. Our Product Standards
            $table->string('standards_title_highlight')->nullable(); // e.g. Standards
            $table->text('standards_description')->nullable();

            // Product Standards - 4 items (Title, Description)
            $table->string('standard1_title')->nullable();
            $table->text('standard1_description')->nullable();

            $table->string('standard2_title')->nullable();
            $table->text('standard2_description')->nullable();

            $table->string('standard3_title')->nullable();
            $table->text('standard3_description')->nullable();

            $table->string('standard4_title')->nullable();
            $table->text('standard4_description')->nullable();

            // What We Stand For Section
            $table->string('stand_title')->nullable(); // e.g. What We Stand For
            $table->string('stand_title_highlight')->nullable(); // e.g. Stand For
            $table->text('stand_description')->nullable();

            // What We Stand For - 4 items (Title, Description)
            $table->string('stand1_title')->nullable();
            $table->text('stand1_description')->nullable();

            $table->string('stand2_title')->nullable();
            $table->text('stand2_description')->nullable();

            $table->string('stand3_title')->nullable();
            $table->text('stand3_description')->nullable();

            $table->string('stand4_title')->nullable();
            $table->text('stand4_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_sections');
    }
};
