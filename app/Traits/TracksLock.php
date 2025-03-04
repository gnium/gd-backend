<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait TracksLock
{
    /**
     * Add lock tracking fields to the fillable array dynamically.
     */
    public function initializeTracksLock()
    {
        $this->mergeFillable(['is_locked', 'locked_by', 'locked_at', 'locked_year', 'locked_month']);
    }

    /**
     * Lock the model.
     */
    public function lock(): void
    {
        if (Auth::check()) {
            $this->is_locked = true;
            $this->locked_by = Auth::id();
            $this->locked_at = now();
            $this->locked_year = now()->year;
            $this->locked_month = now()->format('Y-m');
            $this->save();
        }
    }

    /**
     * Unlock the model.
     */
    public function unlock(): void
    {
        if (Auth::check()) {
            $this->is_locked = false;
            $this->locked_by = Auth::id();
            $this->locked_at = now();
            $this->locked_year = now()->year;
            $this->locked_month = now()->format('Y-m');
            $this->save();
        }
    }
}
