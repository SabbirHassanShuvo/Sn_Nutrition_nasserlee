<?php

namespace App\Http\Controllers\Web\Backend\Cms;

use App\Http\Controllers\Controller;
use App\Models\HowItWorksSection;
use Illuminate\Http\Request;

class HowItWorksController extends Controller
{
    /**
     * Show the edit form for the How It Works section page.
     * Always only one row (singleton pattern).
     */
    public function edit()
    {
        $section = HowItWorksSection::firstOrCreate([]);
        return view('backend.layout.cms.how_it_works.form', compact('section'));
    }

    /**
     * Update the How It Works section page content.
     */
    public function update(Request $request)
    {
        $request->validate([
            // Banner Section
            'banner_small_badge'       => 'nullable|string|max:255',
            'banner_title'             => 'nullable|string|max:255',
            'banner_title_highlight_1' => 'nullable|string|max:255',
            'banner_title_highlight_2' => 'nullable|string|max:255',
            'banner_description'       => 'nullable|string',
            'banner_button_text'       => 'nullable|string|max:255',
            'banner_button_link'       => 'nullable|string|max:255',
            'banner_point_1'           => 'nullable|string|max:255',
            'banner_point_2'           => 'nullable|string|max:255',
            'banner_point_3'           => 'nullable|string|max:255',

            // Banner Right Side Mockups
            'banner_earnings_value'      => 'nullable|string|max:255',
            'banner_earnings_comparison' => 'nullable|string|max:255',
            'banner_earnings_change'     => 'nullable|string|max:255',
            'banner_category1_name'      => 'nullable|string|max:255',
            'banner_category1_percent'   => 'nullable|string|max:255',
            'banner_category2_name'      => 'nullable|string|max:255',
            'banner_category2_percent'   => 'nullable|string|max:255',
            'banner_category3_name'      => 'nullable|string|max:255',
            'banner_category3_percent'   => 'nullable|string|max:255',

            // Stats Section
            'stat1_value' => 'nullable|string|max:255',
            'stat1_label' => 'nullable|string|max:255',
            'stat2_value' => 'nullable|string|max:255',
            'stat2_label' => 'nullable|string|max:255',
            'stat3_value' => 'nullable|string|max:255',
            'stat3_label' => 'nullable|string|max:255',
            'stat4_value' => 'nullable|string|max:255',
            'stat4_label' => 'nullable|string|max:255',

            // Features Section
            'features_title'             => 'nullable|string|max:255',
            'features_title_highlight'   => 'nullable|string|max:255',
            'features_description'       => 'nullable|string',

            'feature1_title'       => 'nullable|string|max:255',
            'feature1_description' => 'nullable|string',
            'feature1_icon'        => 'nullable|image|max:2048',

            'feature2_title'       => 'nullable|string|max:255',
            'feature2_description' => 'nullable|string',
            'feature2_icon'        => 'nullable|image|max:2048',

            'feature3_title'       => 'nullable|string|max:255',
            'feature3_description' => 'nullable|string',
            'feature3_icon'        => 'nullable|image|max:2048',

            'feature4_title'       => 'nullable|string|max:255',
            'feature4_description' => 'nullable|string',
            'feature4_icon'        => 'nullable|image|max:2048',

            'feature5_title'       => 'nullable|string|max:255',
            'feature5_description' => 'nullable|string',
            'feature5_icon'        => 'nullable|image|max:2048',

            'feature6_title'       => 'nullable|string|max:255',
            'feature6_description' => 'nullable|string',

            // Steps Section
            'steps_title'             => 'nullable|string|max:255',
            'steps_title_highlight'   => 'nullable|string|max:255',
            'steps_description'       => 'nullable|string',

            'step1_title'       => 'nullable|string|max:255',
            'step1_description' => 'nullable|string',

            'step2_title'       => 'nullable|string|max:255',
            'step2_description' => 'nullable|string',

            'step3_title'       => 'nullable|string|max:255',
            'step3_description' => 'nullable|string',

            'step4_title'       => 'nullable|string|max:255',
            'step4_description' => 'nullable|string',

            // Tiers Section
            'tiers_title'             => 'nullable|string|max:255',
            'tiers_title_highlight'   => 'nullable|string|max:255',
            'tiers_description'       => 'nullable|string',

            'tier1_name'        => 'nullable|string|max:255',
            'tier1_commission'  => 'nullable|string|max:255',
            'tier1_sales'       => 'nullable|string|max:255',

            'tier2_name'        => 'nullable|string|max:255',
            'tier2_commission'  => 'nullable|string|max:255',
            'tier2_sales'       => 'nullable|string|max:255',

            'tier3_name'        => 'nullable|string|max:255',
            'tier3_commission'  => 'nullable|string|max:255',
            'tier3_sales'       => 'nullable|string|max:255',

            'tier4_name'        => 'nullable|string|max:255',
            'tier4_commission'  => 'nullable|string|max:255',
            'tier4_sales'       => 'nullable|string|max:255',
        ]);

        $section = HowItWorksSection::firstOrCreate([]);
        
        $data = $request->except([
            'feature1_icon',
            'feature2_icon',
            'feature3_icon',
            'feature4_icon',
            'feature5_icon',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $fieldName = 'feature' . $i . '_icon';
            if ($request->hasFile($fieldName)) {
                if ($section->$fieldName) {
                    $data[$fieldName] = fileUpdate($request->file($fieldName), 'cms/howitworks', $section->$fieldName);
                } else {
                    $data[$fieldName] = fileUpload($request->file($fieldName), 'cms/howitworks');
                }
            }
        }

        $section->update($data);

        return redirect()
            ->route('backend.how-it-works.edit')
            ->with('success', 'How It Works page content updated successfully.');
    }
}
