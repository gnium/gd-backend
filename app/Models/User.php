<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Roles\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Referrals\Models\Referrer;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company',
        'website',
        'deal_stage',
        'publisher_id',
        'is_active',
        'profile_picture_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_picture_url'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'company' => 'string',
            'website' => 'string',
            'is_active' => 'boolean'
        ];
    }

    public function referrer()
    {
        return $this->hasOne(Referrer::class);
    }

    /**
     * Define a many-to-many relationship with Role.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')->with('navigationItems');
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function getProfilePictureUrlAttribute()
    {
        // Toma la URL base del .env y combina con el path
        $appUrl = config('app.url');
        
        $profilePicturePath = $this->attributes['profile_picture_path'] ?? null;

        if ($profilePicturePath) {
            return rtrim($appUrl, '/') . '/' . ltrim($profilePicturePath, '/');
        }

        // Valor por defecto si no hay foto de perfil
        return rtrim($appUrl, '/') . '/storage/default-profile-pictures/profile-picture.png';
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
        return $query->where('name', 'like', "%{$term}%")
                     ->orWhere('email', 'like', "%{$term}%");
    }
}
