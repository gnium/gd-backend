<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Roles\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin', // Full access to the platform, manages users, roles, and system settings
                'display_name' => 'Administrador',
            ],
            [
                'name' => 'referrer', // User who refers others and earns commissions
                'display_name' => 'Referidor',
            ],
            [
                'name' => 'buyer', // User who purchases domains but does not necessarily refer others
                'display_name' => 'Comprador',
            ],
            [
                'name' => 'affiliateManager', // Manages affiliates, reviews referrals, and oversees commissions
                'display_name' => 'Administrador de Afiliados',
            ],
            [
                'name' => 'moderator', // Reviews and approves referrals, detects potential fraud
                'display_name' => 'Moderador',
            ],
            [
                'name' => 'accountant', // Handles commission payments and financial reports
                'display_name' => 'Contable',
            ],
        ];
        

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
