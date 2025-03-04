<?php

namespace Modules\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleNavigationItem extends Model
{
    protected $fillable = ['role_id', 'navigation_item'];

    /**
     * Define the relationship with the Role model.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
