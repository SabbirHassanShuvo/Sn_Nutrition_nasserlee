<?php

namespace App\Http\Controllers\Web\Backend\Cms;

use App\Http\Controllers\Controller;
use App\Models\QualityControlSection;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    /**
     * Show the edit form for the Quality Control section.
     * There is always only one row (singleton pattern) — create it if it doesn't exist.
     */
    public function qualityControlEdit()
    {
        $section = QualityControlSection::firstOrCreate([]);
        return view('backend.layout.cms.home_page.quality_control', compact('section'));
    }

    /**
     * Update the Quality Control section.
     */
    public function qualityControlUpdate(Request $request)
    {
        $request->validate([
            'title'                       => 'nullable|string|max:255',
            'title_highlight'             => 'nullable|string|max:255',
            'description'                 => 'nullable|string',
            'image'                       => 'nullable|image|max:4096',
            'card1_title'                 => 'nullable|string|max:255',
            'card1_description'           => 'nullable|string',
            'card1_description_highlight' => 'nullable|string|max:500',
            'card2_title'                 => 'nullable|string|max:255',
            'card2_description'           => 'nullable|string',
            'card2_description_highlight' => 'nullable|string|max:500',
            'card3_title'                 => 'nullable|string|max:255',
            'card3_description'           => 'nullable|string',
            'card3_description_highlight' => 'nullable|string|max:500',
        ]);

        $section = QualityControlSection::firstOrCreate([]);
        $data    = $request->except('image');

        if ($request->hasFile('image')) {
            if ($section->image) {
                $data['image'] = fileUpdate($request->file('image'), 'cms/quality_control', $section->image);
            } else {
                $data['image'] = fileUpload($request->file('image'), 'cms/quality_control');
            }
        }

        $section->update($data);

        return redirect()
            ->route('backend.home-page.quality-control.edit')
            ->with('success', 'Quality Control section updated successfully.');
    }
}
