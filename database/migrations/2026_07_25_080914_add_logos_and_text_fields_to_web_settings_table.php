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
        Schema::table('web_settings', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn([
                'navbar_logo',
                'footer_logo',
                'footer_description',
                'copyright_text',
                'footer_phone',
                'footer_email',
                'footer_address'
            ]);
        });
    }
};
