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

            'cms_faq',
            'cms_pages',

            'setting_profile',
            'setting_system',
            'setting_mail',

            'role_management',
            'user_management',
        ];

        // Create permissions if not already exist
        foreach (array_unique($permissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
