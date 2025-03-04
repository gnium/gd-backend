<?php

namespace Modules\Referrals\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralClick extends Model
{
    protected $fillable = [
        'referral_link_id',
        'code',
        'ip_address', 
        'user_agent', 
        'clicked_at',
        'action_completed',
        'completed_at'
    ];

    /**
     * Get the referral link associated with this log.
     */
    public function referralLink(): BelongsTo
    {
        return $this->belongsTo(ReferralLink::class);
    }
}
