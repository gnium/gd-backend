<?php

namespace Modules\Roles\Models;

use App\Models\User;
use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use SoftDeletes, TracksUser;
    protected $fillable = [
        'name', // Unique system name of the role
        'display_name', // Human-readable name of the role
    ];
    protected $appends = [
        'navigation_item_list'
    ];
    /**
     * Define a many-to-many relationship with User.
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * Define a one-to-many relationship with RoleNavigationItem.
     */
    public function navigationItems(): HasMany
    {
        return $this->hasMany(RoleNavigationItem::class);
    }

    /**
     * Get navigation items as a simple array of item names.
     */
    public function getNavigationItemListAttribute(): array
    {
        return $this->navigationItems->pluck('navigation_item')->toArray();
    }
}
