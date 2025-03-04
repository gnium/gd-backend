<?php

namespace Modules\Referrals\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Referrals\Models\ReferralAction;

class ReferralActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = [
            [
                'name' => 'register',
                'display_name' => 'Registro de usuario',
                'path_template' => '/auth/register?ref={{referrer_code}}',
                'reward_amount' => 5.00,
                'currency' => 'USD',
            ],
            [
                'name' => 'buyDomain',
                'display_name' => 'Compra de dominio',
                'path_template' => '/domains/checkout?ref={{referrer_code}}',
                'reward_amount' => 10.00,
                'currency' => 'USD',
            ],
        ];

        foreach ($actions as $action) {
            ReferralAction::firstOrCreate(['name' => $action['name']], $action);
        }
    }
}
