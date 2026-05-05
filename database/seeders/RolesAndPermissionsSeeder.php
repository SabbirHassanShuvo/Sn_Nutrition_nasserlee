<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'manage_orders',
            'view_orders',
            'manage_products',
            'view_stock',
            'pick_pack_stock',
            'send_delivery_ticket',
            'view_partner_data',
            'approve_payouts',
            'manage_site_settings',
            'manage_users',
            'view_revenue_analytics',
            'affiliate_features'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // create roles and assign created permissions

        // Normal Admin
        $normalAdmin = Role::findOrCreate('normal_admin');
        $normalAdmin->givePermissionTo([
            'manage_orders',
            'view_orders',
            'view_stock',
            'send_delivery_ticket'
        ]);

        // Fulfillment
        $fulfillment = Role::findOrCreate('fulfillment');
        $fulfillment->givePermissionTo([
            'view_orders',
            'pick_pack_stock'
        ]);

        // Health Professional
        $healthProfessional = Role::findOrCreate('health_professional');
        $healthProfessional->givePermissionTo([
            'affiliate_features'
        ]);

        // Super Admin
        $superAdmin = Role::findOrCreate('super_admin');
        // gets all permissions via Gate::before rule in AuthServiceProvider/AppServiceProvider
    }
}
