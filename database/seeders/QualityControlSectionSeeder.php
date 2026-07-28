<?php

namespace Database\Seeders;

use App\Models\QualityControlSection;
use Illuminate\Database\Seeder;

class QualityControlSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds the singleton Quality Control section shown on the homepage.
     */
    public function run(): void
    {
        // Singleton — truncate and re-insert the one row
        QualityControlSection::truncate();

        QualityControlSection::create([
            // ── Left-side content ──────────────────────────────────────
            'title'           => 'Uncompromising quality control.',
            'title_highlight' => 'quality control.',
            'description'     => 'Every formula is rigorously tested at multiple stages to ensure absolute purity and potency. We believe in complete transparency for your peace of mind.',
            'image'           => 'assets/images/banner/laboratory-image.png',

            // ── Feature Card 1 — Third-Party Tested ───────────────────
            'card1_title'                 => 'Third-Party Tested',
            'card1_description'           => 'Independently tested for purity, potency, and safety before it reaches your door.',
            'card1_description_highlight' => 'purity, potency, and safety',

            // ── Feature Card 2 — Clinically Studied ───────────────────
            'card2_title'                 => 'Clinically Studied',
            'card2_description'           => 'Active ingredients in exact dosages proven effective in clinical research.',
            'card2_description_highlight' => 'in exact dosages proven effective',

            // ── Feature Card 3 — Clean Formulation ────────────────────
            'card3_title'                 => 'Clean Formulation',
            'card3_description'           => 'Maximum absorption without synthetic fillers, artificial colors, or common allergens. Crafted with the environment and your body in mind.',
            'card3_description_highlight' => 'without synthetic fillers, artificial colors, or common allergens.',
        ]);
    }
}
