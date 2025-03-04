<?php

namespace Modules\Referrals\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralAction extends Model
{
    protected $fillable = ['name', 'display_name', 'path_template', 'reward_amount', 'currency'];

    /**
     * Get the referral links associated with this action.
     */
    public function referralLinks(): HasMany
    {
        return $this->hasMany(ReferralLink::class, 'action_id');
    }

    /**
     * Get the full URL based on the environment domain.
     */
    public function getFullUrlAttribute(): string
    {
        return config('app.referral_base_url') . $this->path_template;
    }

    /**
     * Scope to filter products by active status.
     */
    public function scopeActive($query, $isActive)
    {
        return $query->where('is_active', $isActive);
    }

    /**
     * Scope to search products by name or slug.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('display_name', 'like', "%{$term}%")
                     ->orWhere('path_template', 'like', "%{$term}%");
    }
}
