<?php

namespace Database\Seeders;

use App\Models\WebSetting;
use Illuminate\Database\Seeder;

class WebSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WebSetting::updateOrCreate(
            ['id' => 1],
            [
                'top_banner_text'   => 'Free Delivery Starting From 1000 dh',
                'top_banner_status' => 1,
                
                // Logos
                'navbar_logo'       => 'assets/images/banners/logo_white.png',
                'footer_logo'       => 'assets/images/banners/logo_white.png',

                // Footer Content
                'footer_description'=> 'Modern science meets clean ingredients. Premium supplements built for efficacy, transparency, and your long-term wellbeing.',
                'copyright_text'    => '© 2026 SN Nutrition. All rights reserved.',

                // Footer Contacts
                'footer_phone'      => '+44 7824 739607',
                'footer_email'      => 'contact@snnutrition.com',
                'footer_address'    => 'Casablanca, Morocco',

                // Social Links
                'facebook_url'      => 'https://facebook.com/snnutrition',
                'instagram_url'     => 'https://instagram.com/snnutrition',
                'twitter_url'       => 'https://twitter.com/snnutrition',
                'whatsapp_url'      => '+447824739607',
                'linkedin_url'      => 'https://linkedin.com/company/snnutrition',
            ]
        );
    }
}
