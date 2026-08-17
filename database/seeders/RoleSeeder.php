<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        $superAdmin = Role::create(['name' => 'super_admin']);
        $admin = Role::create(['name' => 'admin']);
        $healthProfessional = Role::create(['name' => 'health_professional']);
        $user = Role::create(['name' => 'user']);

        // Give super_admin all permissions
        $superAdmin->givePermissionTo(Permission::all());

        // Give admin specific permissions (everything except system settings, roles, and system users)
        $admin->givePermissionTo([
            'dashboard_stats',
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
            
            // CMS & FAQ
            'cms_banner',
            'cms_home_page',
            'cms_about_page',
            'cms_how_it_works',
            'cms_contact_page',
            'blog_manage',
            'cms_pages',
            'cms_faq',
            'offers_manage',
            'offer_manage',
        ]);
    }
}
