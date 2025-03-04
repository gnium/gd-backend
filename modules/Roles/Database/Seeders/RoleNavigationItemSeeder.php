<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Roles\Models\RoleNavigationItem;
use Modules\Roles\Models\Role;

class RoleNavigationItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $navigationItems = [
            'admin' => ['dashboard', 'notifications', 'users', 'referrers', 'referralActions'],
            //'referrer' => ['dashboard', 'myReferralLinks', 'myReferralLinkLogs'],
            'referrer' => ['dashboard', 'referralLinks', 'referralClicks'],
            'buyer' => ['dashboard'],
        ];

        foreach ($navigationItems as $roleName => $items) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                foreach ($items as $item) {
                    RoleNavigationItem::firstOrCreate([
                        'role_id' => $role->id,
                        'navigation_item' => $item,
                    ]);
                }
            }
        }
    }
}
