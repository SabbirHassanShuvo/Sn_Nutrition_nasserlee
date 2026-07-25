<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;

class WebSettingController extends Controller
{
    public function index()
    {
        $settings = WebSetting::first() ?? new WebSetting();
        return view('backend.layout.settings.web_setting', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'navbar_logo'        => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'footer_logo'        => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'top_banner_text'    => 'nullable|string|max:500',
            'top_banner_status'  => 'required|integer|in:0,1',
            'footer_description' => 'nullable|string',
            'copyright_text'     => 'nullable|string|max:255',
            'footer_phone'       => 'nullable|string|max:50',
            'footer_email'       => 'nullable|email|max:255',
            'footer_address'     => 'nullable|string|max:255',
            'facebook_url'       => 'nullable|url|max:255',
            'instagram_url'      => 'nullable|url|max:255',
            'twitter_url'        => 'nullable|url|max:255',
            'whatsapp_url'       => 'nullable|string|max:255',
            'linkedin_url'       => 'nullable|url|max:255',
        ]);

        $settings = WebSetting::first() ?? new WebSetting();

        // Handle navbar logo upload
        if ($request->hasFile('navbar_logo')) {
            if ($settings->navbar_logo) {
                $settings->navbar_logo = fileUpdate($request->file('navbar_logo'), 'settings/web', $settings->navbar_logo);
            } else {
                $settings->navbar_logo = fileUpload($request->file('navbar_logo'), 'settings/web');
            }
        }

        // Handle footer logo upload
        if ($request->hasFile('footer_logo')) {
            if ($settings->footer_logo) {
                $settings->footer_logo = fileUpdate($request->file('footer_logo'), 'settings/web', $settings->footer_logo);
            } else {
                $settings->footer_logo = fileUpload($request->file('footer_logo'), 'settings/web');
            }
        }

        $data = $request->except(['navbar_logo', 'footer_logo']);
        $settings->fill($data);
        $settings->save();

        return redirect()->route('backend.settings.web-setting.index')->with('success', 'Updated Web Settings Successfully.');
    }
}
