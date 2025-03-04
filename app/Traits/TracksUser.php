<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait TracksUser
{
    /**
     * Boot the trait to add created_by and updated_by tracking.
     */
    public static function bootTracksUser()
    {
        static::creating(function (Model $model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function (Model $model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * Add user tracking fields to the fillable array dynamically.
     */
    public function initializeTracksUser()
    {
        $this->mergeFillable(['created_by', 'updated_by']);
    }
}
