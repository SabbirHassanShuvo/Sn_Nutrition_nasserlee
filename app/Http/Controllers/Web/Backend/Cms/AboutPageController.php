<?php

namespace App\Http\Controllers\Web\Backend\Cms;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    /**
     * Show the edit form for the About section page.
     * Always only one row (singleton pattern).
     */
    public function edit()
    {
        $section = AboutSection::firstOrCreate([]);
        return view('backend.layout.cms.about_page.form', compact('section'));
    }

    /**
     * Update the About section page content.
     */
    public function update(Request $request)
    {
        $request->validate([
            'story_badge'             => 'nullable|string|max:255',
            'story_title'             => 'nullable|string|max:255',
            'story_title_highlight'   => 'nullable|string|max:255',
            'story_description'       => 'nullable|string',
            'story_image'             => 'nullable|image|max:4096',

            'mission_title'           => 'nullable|string|max:255',
            'mission_title_highlight' => 'nullable|string|max:255',
            'mission_description'     => 'nullable|string',

            'stat1_value'             => 'nullable|string|max:255',
            'stat1_label'             => 'nullable|string|max:255',
            'stat2_value'             => 'nullable|string|max:255',
            'stat2_label'             => 'nullable|string|max:255',
            'stat3_value'             => 'nullable|string|max:255',
            'stat3_label'             => 'nullable|string|max:255',
            'stat4_value'             => 'nullable|string|max:255',
            'stat4_label'             => 'nullable|string|max:255',

            'standards_title'             => 'nullable|string|max:255',
            'standards_title_highlight'   => 'nullable|string|max:255',
            'standards_description'       => 'nullable|string',

            'standard1_icon'              => 'nullable|string|max:255',
            'standard1_title'             => 'nullable|string|max:255',
            'standard1_description'       => 'nullable|string',

            'standard2_icon'              => 'nullable|string|max:255',
            'standard2_title'             => 'nullable|string|max:255',
            'standard2_description'       => 'nullable|string',

            'standard3_icon'              => 'nullable|string|max:255',
            'standard3_title'             => 'nullable|string|max:255',
            'standard3_description'       => 'nullable|string',

            'standard4_icon'              => 'nullable|string|max:255',
            'standard4_title'             => 'nullable|string|max:255',
            'standard4_description'       => 'nullable|string',

            'stand_title'                 => 'nullable|string|max:255',
            'stand_title_highlight'       => 'nullable|string|max:255',
            'stand_description'           => 'nullable|string',

            'stand1_icon'                 => 'nullable|string|max:255',
            'stand1_title'                => 'nullable|string|max:255',
            'stand1_description'          => 'nullable|string',

            'stand2_icon'                 => 'nullable|string|max:255',
            'stand2_title'                => 'nullable|string|max:255',
            'stand2_description'          => 'nullable|string',

            'stand3_icon'                 => 'nullable|string|max:255',
            'stand3_title'                => 'nullable|string|max:255',
            'stand3_description'          => 'nullable|string',

            'stand4_icon'                 => 'nullable|string|max:255',
            'stand4_title'                => 'nullable|string|max:255',
            'stand4_description'          => 'nullable|string',
        ]);

        $section = AboutSection::firstOrCreate([]);
        $data = $request->except('story_image');

        if ($request->hasFile('story_image')) {
            if ($section->story_image) {
                $data['story_image'] = fileUpdate($request->file('story_image'), 'cms/about', $section->story_image);
            } else {
                $data['story_image'] = fileUpload($request->file('story_image'), 'cms/about');
            }
        }

        $section->update($data);

        return redirect()
            ->route('backend.about-us.edit')
            ->with('success', 'About Us page content updated successfully.');
    }
}
