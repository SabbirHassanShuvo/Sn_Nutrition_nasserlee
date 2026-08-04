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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->string('badge')->nullable();
            $table->string('promo_code')->nullable();
            $table->string('bg_color')->default('#10b981');
            $table->string('banner_image')->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->enum('position', ['top_banner', 'normal_deal'])->default('normal_deal');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
