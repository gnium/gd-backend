<?php

namespace Modules\Referrals\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReferralLink extends Model
{
    protected $fillable = ['referrer_id', 'action_id'];

    protected $appends = ['formatted_path', 'full_url'];

    /**
     * Get the referrer of this link.
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(Referrer::class);
    }

    /**
     * Get the referral action associated with this link.
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(ReferralAction::class, 'action_id');
    }

    /**
     * Get the full path replacing the referrer code.
     */
    public function getFormattedPathAttribute(): string
    {
        return str_replace('{{referrer_code}}', $this->referrer->code, $this->action->path_template);
    }

    /**
     * Get the full URL replacing the referrer code.
     */
    public function getFullUrlAttribute(): string
    {
        return config('app.referral_base_url') . $this->formatted_path;
    }
}

