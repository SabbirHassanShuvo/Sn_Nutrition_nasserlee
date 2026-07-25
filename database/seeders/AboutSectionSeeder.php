<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use Illuminate\Database\Seeder;

class AboutSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutSection::firstOrCreate([], [
            'story_badge' => 'Our Story',
            'story_title' => 'Nutrition you can trust',
            'story_title_highlight' => 'trust',
            'story_description' => 'SN Nutrition was founded with one mission — to make premium, honest, and effective supplements accessible to everyone who cares about their health.',
            'story_image' => null,

            'mission_title' => 'Our Mission',
            'mission_title_highlight' => 'Mission',
            'mission_description' => 'We believe wellness shouldn\'t be complicated. That\'s why we craft each supplement with carefully sourced, clinically dosed ingredients — and put them through rigorous testing before they reach you. From elite athletes to busy parents, our community trusts us because we never compromise. No shortcuts, no marketing fluff — just real nutrition that works.',

            'stat1_value' => '4,200+',
            'stat1_label' => 'Health pros',
            'stat2_value' => '$8.4M',
            'stat2_label' => 'Paid out in 2024',
            'stat3_value' => '98.6%',
            'stat3_label' => 'On-time payouts',
            'stat4_value' => '25%',
            'stat4_label' => 'Top commission',

            'standards_title' => 'Our Product Standards',
            'standards_title_highlight' => 'Standards',
            'standards_description' => 'Every product meets strict quality standards to support your health with confidence.',

            'standard1_icon' => 'ri-shield-check-line',
            'standard1_title' => 'Halal Certified',
            'standard1_description' => 'All products are certified Halal and meet trusted dietary requirements.',

            'standard2_icon' => 'ri-dna-line',
            'standard2_title' => 'Non-GMO',
            'standard2_description' => 'Made without genetically modified ingredients for cleaner supplement experience.',

            'standard3_icon' => 'ri-leaf-line',
            'standard3_title' => 'Gluten-Free',
            'standard3_description' => 'Formulated without gluten, making them suitable for gluten-conscious lifestyles.',

            'standard4_icon' => 'ri-drop-line',
            'standard4_title' => 'No Additives',
            'standard4_description' => 'No artificial fillers, colors, preservatives, or unnecessary additives.',

            'stand_title' => 'What We Stand For',
            'stand_title_highlight' => 'Stand For',
            'stand_description' => 'The principles that guide every product we make.',

            'stand1_icon' => 'ri-flask-line',
            'stand1_title' => 'Science-Backed',
            'stand1_description' => 'Every formula is grounded in peer-reviewed research and clinical evidence.',

            'stand2_icon' => 'ri-seedling-line',
            'stand2_title' => 'Clean Ingredients',
            'stand2_description' => 'No artificial fillers, dyes, or hidden additives — just what your body needs.',

            'stand3_icon' => 'ri-shield-cross-line',
            'stand3_title' => 'Third-Party Tested',
            'stand3_description' => 'Independently verified for purity, potency, and safety in every batch.',

            'stand4_icon' => 'ri-heart-line',
            'stand4_title' => 'Built for Wellbeing',
            'stand4_description' => 'Designed to support your long-term health, not chase quick results.',
        ]);
    }
}
