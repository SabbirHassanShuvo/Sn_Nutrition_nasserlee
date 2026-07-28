<?php

namespace App\Http\Controllers\Web\Backend\Cms;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    /**
     * Show the edit form for the Contact Us CMS page.
     */
    public function edit()
    {
        $settings = WebSetting::firstOrCreate([]);
        return view('backend.layout.cms.contact_page', compact('settings'));
    }

    /**
     * Update the Contact Us CMS page settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'contact_badge'           => 'nullable|string|max:255',
            'contact_title'           => 'nullable|string|max:255',
            'contact_title_highlight' => 'nullable|string|max:255',
            'contact_subtitle'        => 'nullable|string',
            'footer_phone'            => 'nullable|string|max:50',
            'contact_phone_hours'     => 'nullable|string|max:255',
            'footer_email'            => 'nullable|email|max:255',
            'contact_email_response'  => 'nullable|string|max:255',
            'footer_address'          => 'nullable|string|max:255',
            'contact_address_details' => 'nullable|string|max:255',
            'contact_map_iframe'      => 'nullable|string',
            'contact_follow_title'    => 'nullable|string|max:255',
            'contact_follow_subtitle' => 'nullable|string|max:255',
        ]);

        $settings = WebSetting::firstOrCreate([]);
        $settings->update($request->only([
            'contact_badge',
            'contact_title',
            'contact_title_highlight',
            'contact_subtitle',
            'footer_phone',
            'contact_phone_hours',
            'footer_email',
            'contact_email_response',
            'footer_address',
            'contact_address_details',
            'contact_map_iframe',
            'contact_follow_title',
            'contact_follow_subtitle',
        ]));

        return redirect()
            ->route('backend.contact-us.edit')
            ->with('success', 'Contact Us page content updated successfully.');
    }
}
