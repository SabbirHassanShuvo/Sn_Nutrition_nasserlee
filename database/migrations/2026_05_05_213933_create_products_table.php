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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->string('form')->nullable(); // Capsule, Tablet, etc.
            $table->integer('servings')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->boolean('is_vegan')->default(false);
            $table->boolean('in_stock')->default(true);
            $table->string('main_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
