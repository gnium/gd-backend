<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Roles\Database\Seeders\RoleSeeder;
use Modules\Roles\Database\Seeders\RoleNavigationItemSeeder;
use Modules\Referrals\Database\Seeders\ReferralActionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(RoleNavigationItemSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ReferralActionSeeder::class);
        
    }
}
