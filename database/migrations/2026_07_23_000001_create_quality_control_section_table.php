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
        Schema::create('quality_control_section', function (Blueprint $table) {
            $table->id();

            // Left side content
            $table->string('title')->nullable();
            $table->string('title_highlight')->nullable();          // word wrapped in green italic
            $table->text('description')->nullable();
            $table->string('image')->nullable();                    // doctor / lab image

            // Feature Card 1
            $table->string('card1_title')->nullable();
            $table->text('card1_description')->nullable();
            $table->string('card1_description_highlight')->nullable();

            // Feature Card 2
            $table->string('card2_title')->nullable();
            $table->text('card2_description')->nullable();
            $table->string('card2_description_highlight')->nullable();

            // Feature Card 3
            $table->string('card3_title')->nullable();
            $table->text('card3_description')->nullable();
            $table->string('card3_description_highlight')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_control_section');
    }
};
