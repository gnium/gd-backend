<?php

namespace Modules\Referrals\Services;

use Modules\Referrals\Models\Referrer;
use Modules\Referrals\Models\ReferralAction;
use Modules\Referrals\Models\ReferralLink;
use App\Models\User;
use Illuminate\Support\Str;

class ReferralService
{
    /**
     * Assign referrer role and generate referral links.
     */
    public function createReferrerAndLinks(User $user): void
    {
        // Create Referrer entry
        $referrer = Referrer::firstOrCreate(
            ['user_id' => $user->id],
            ['code' => Str::random(10)]
        );

        // Get all referral actions and create links
        $actions = ReferralAction::all();
        foreach ($actions as $action) {
            ReferralLink::firstOrCreate([
                'referrer_id' => $referrer->id,
                'action_id' => $action->id
            ]);
        }
    }
}
