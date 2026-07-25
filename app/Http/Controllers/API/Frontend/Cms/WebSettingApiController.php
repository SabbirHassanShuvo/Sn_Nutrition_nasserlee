<?php

namespace App\Http\Controllers\API\Frontend\Cms;

use App\Http\Controllers\API\BaseController;
use App\Models\WebSetting;
use Illuminate\Http\Request;

class WebSettingApiController extends BaseController
{
    /**
     * Get Web settings data (Navbar/Footer logos, banner, text and social details).
     */
    public function getWebSettings()
    {
        try {
            $settings = WebSetting::first();

            if (!$settings) {
                return $this->sendResponse(null, 'No web settings found.');
            }

            $data = [
                'navbar_logo'       => $settings->navbar_logo ? asset($settings->navbar_logo) : null,
                'footer_logo'       => $settings->footer_logo ? asset($settings->footer_logo) : null,
                'top_banner' => [
                    'text'   => $settings->top_banner_text,
                    'status' => (int) $settings->top_banner_status,
                ],
                'footer' => [
                    'description'   => $settings->footer_description,
                    'copyright'     => $settings->copyright_text,
                    'phone'         => $settings->footer_phone,
                    'email'         => $settings->footer_email,
                    'address'       => $settings->footer_address,
                ],
                'social_links' => [
                    'facebook'  => $settings->facebook_url,
                    'instagram' => $settings->instagram_url,
                    'twitter'   => $settings->twitter_url,
                    'whatsapp'  => $settings->whatsapp_url,
                    'linkedin'  => $settings->linkedin_url,
                ]
            ];

            return $this->sendResponse($data, 'Web settings fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch web settings.', ['error' => $e->getMessage()]);
        }
    }
}
