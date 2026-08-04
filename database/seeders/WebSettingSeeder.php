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
                'footer_phone'      => '+44 7824 7394520',
                'footer_email'      => 'contact@snnutrition.com',
                'footer_address'    => 'Casablanca, Morocco',

                // Contact Us CMS settings
                'contact_badge'     => "We're here to help",
                'contact_title'     => 'Get in',
                'contact_title_highlight' => 'touch',
                'contact_subtitle'  => 'Questions about product an order, or a partnership? Our wellness team is just a message away.',
                'contact_phone_hours'=> 'Mon - Sat, 9:00 - 12:00',
                'contact_email_response'=> 'Replies within a few hours',
                'contact_address_details'=> 'Boulevard, 20000',
                'contact_map_iframe'=> 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d261786.41811593325!2d-7.586992!3d33.572287!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7cd4778aa113b%3A0xb06c1d84f310fd3!2sCasablanca%2C%20Morocco!5e1!3m2!1sen!2sbd!4v1785033037818!5m2!1sen!2sbd',
                'contact_follow_title'=> 'Follow us',
                'contact_follow_subtitle'=> 'Tips, drops and behind the scenes.',

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
