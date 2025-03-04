<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait TracksDate
{
    /**
     * Boot the trait to add year and month tracking.
     */
    public static function bootTracksDate()
    {
        static::creating(function (Model $model) {
            $now = now();
            $model->created_year = $now->year;
            $model->created_month = $now->format('Y-m');
            $model->updated_year = $now->year;
            $model->updated_month = $now->format('Y-m');
        });

        static::updating(function (Model $model) {
            $now = now();
            $model->updated_year = $now->year;
            $model->updated_month = $now->format('Y-m');
        });
    }

    /**
     * Add date tracking fields to the fillable array dynamically.
     */
    public function initializeTracksDate()
    {
        $this->mergeFillable(['created_year', 'created_month', 'updated_year', 'updated_month']);
    }
}
