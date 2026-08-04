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
        Schema::create('onboarding_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('onboarding_question_id')->constrained('onboarding_questions')->onDelete('cascade');
            $table->string('answer_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_answers');
    }
};
