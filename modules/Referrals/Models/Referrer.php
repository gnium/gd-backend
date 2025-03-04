<?php

namespace Modules\Referrals\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referrer extends Model
{
    protected $fillable = ['user_id', 'code'];

    /**
     * Get the user associated with this referrer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the referral links associated with this referrer.
     */
    public function referralLinks(): HasMany
    {
        return $this->hasMany(ReferralLink::class);
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
