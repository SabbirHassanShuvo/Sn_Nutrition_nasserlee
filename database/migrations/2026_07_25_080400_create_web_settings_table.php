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
        Schema::create('web_settings', function (Blueprint $table) {
            $table->id();
            // Header top banner settings
            $table->string('top_banner_text')->nullable();
            $table->tinyInteger('top_banner_status')->default(1); // 1 = active, 0 = inactive

            // Footer settings
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->string('linkedin_url')->nullable();

            // Logos
            $table->string('navbar_logo')->nullable();
            $table->string('footer_logo')->nullable();

            // Footer text
            $table->text('footer_description')->nullable();
            $table->string('copyright_text')->nullable();

            // Footer contact info
            $table->string('footer_phone')->nullable();
            $table->string('footer_email')->nullable();
            $table->string('footer_address')->nullable();

            // Contact Us CMS settings
            $table->string('contact_badge')->nullable();
            $table->string('contact_title')->nullable();
            $table->string('contact_title_highlight')->nullable();
            $table->text('contact_subtitle')->nullable();
            $table->string('contact_phone_hours')->nullable();
            $table->string('contact_email_response')->nullable();
            $table->string('contact_address_details')->nullable();
            $table->text('contact_map_iframe')->nullable();
            $table->string('contact_follow_title')->nullable();
            $table->string('contact_follow_subtitle')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_settings');
    }
};
