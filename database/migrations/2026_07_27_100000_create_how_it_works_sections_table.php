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
        Schema::create('how_it_works_sections', function (Blueprint $table) {
            $table->id();

            // Banner Section
            $table->string('banner_small_badge')->nullable();
            $table->string('banner_title')->nullable();
            $table->string('banner_title_highlight_1')->nullable();
            $table->string('banner_title_highlight_2')->nullable();
            $table->text('banner_description')->nullable();
            $table->string('banner_button_text')->nullable();
            $table->string('banner_button_link')->nullable();
            $table->string('banner_point_1')->nullable();
            $table->string('banner_point_2')->nullable();
            $table->string('banner_point_3')->nullable();

            // Banner Right-Side Dynamic Value Mockups
            $table->string('banner_earnings_value')->nullable();
            $table->string('banner_earnings_comparison')->nullable();
            $table->string('banner_earnings_change')->nullable();
            $table->string('banner_category1_name')->nullable();
            $table->string('banner_category1_percent')->nullable();
            $table->string('banner_category2_name')->nullable();
            $table->string('banner_category2_percent')->nullable();
            $table->string('banner_category3_name')->nullable();
            $table->string('banner_category3_percent')->nullable();

            // Stats Counter Section (4 items)
            $table->string('stat1_value')->nullable();
            $table->string('stat1_label')->nullable();
            $table->string('stat2_value')->nullable();
            $table->string('stat2_label')->nullable();
            $table->string('stat3_value')->nullable();
            $table->string('stat3_label')->nullable();
            $table->string('stat4_value')->nullable();
            $table->string('stat4_label')->nullable();

            // Features Section (6 items)
            $table->string('features_title')->nullable();
            $table->string('features_title_highlight')->nullable();
            $table->text('features_description')->nullable();

            $table->string('feature1_title')->nullable();
            $table->text('feature1_description')->nullable();
 

            $table->string('feature2_title')->nullable();
            $table->text('feature2_description')->nullable();


            $table->string('feature3_title')->nullable();
            $table->text('feature3_description')->nullable();
      

            $table->string('feature4_title')->nullable();
            $table->text('feature4_description')->nullable();
       

            $table->string('feature5_title')->nullable();
            $table->text('feature5_description')->nullable();
          

            $table->string('feature6_title')->nullable();
            $table->text('feature6_description')->nullable();

            // Steps Section (4 items)
            $table->string('steps_title')->nullable();
            $table->string('steps_title_highlight')->nullable();
            $table->text('steps_description')->nullable();

            $table->string('step1_title')->nullable();
            $table->text('step1_description')->nullable();

            $table->string('step2_title')->nullable();
            $table->text('step2_description')->nullable();

            $table->string('step3_title')->nullable();
            $table->text('step3_description')->nullable();

            $table->string('step4_title')->nullable();
            $table->text('step4_description')->nullable();

            // Tiers Section (4 items)
            $table->string('tiers_title')->nullable();
            $table->string('tiers_title_highlight')->nullable();
            $table->text('tiers_description')->nullable();

            $table->string('tier1_name')->nullable();
            $table->string('tier1_commission')->nullable();
            $table->string('tier1_sales')->nullable();

            $table->string('tier2_name')->nullable();
            $table->string('tier2_commission')->nullable();
            $table->string('tier2_sales')->nullable();

            $table->string('tier3_name')->nullable();
            $table->string('tier3_commission')->nullable();
            $table->string('tier3_sales')->nullable();

            $table->string('tier4_name')->nullable();
            $table->string('tier4_commission')->nullable();
            $table->string('tier4_sales')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('how_it_works_sections');
    }
};
