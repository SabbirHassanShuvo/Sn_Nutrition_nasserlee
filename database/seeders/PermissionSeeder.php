<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'dashboard_stats',

            // Sidebar Core Modules
            'user_management',
            'category_manage',
            'categories_manage',
            'brand_manage',
            'brands_manage',
            'product_manage',
            'products_manage',
            'order_manage',
            'orders_manage',
            'promo_code_manage',
            'promo_codes_manage',
            'specialist_manage',
            'specialists_manage',
            'consultation_manage',
            'consultations_manage',
            'gym_manage',
            'gyms_manage',
            'pharmacy_manage',
            'pharmacies_manage',
            'contact_manage',
            'contact_submissions_manage',
            'subscriber_manage',
            'subscribers_manage',
            'onboarding_manage',
            'onboarding_options_manage',

            // CMS
            'cms_banner',
            'cms_home_page',
            'cms_about_page',
            'cms_how_it_works',
            'cms_contact_page',
            'blog_manage',
            'cms_pages',
            'cms_faq',

            // Settings
            'setting_profile',
            'setting_system',
            'setting_mail',
            
            // Roles/Permissions control
            'role_management',
            'offers_manage',
            'offer_manage',
        ];

        // Create permissions if not already exist
        foreach (array_unique($permissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
