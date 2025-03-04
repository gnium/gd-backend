<?php

use Illuminate\Support\Facades\Route;
use Modules\Referrals\Http\Controllers\ReferrerController;
use Modules\Referrals\Http\Controllers\ReferralActionController;
use Modules\Referrals\Http\Controllers\ReferralClickController;
use Modules\Referrals\Http\Controllers\MyReferralLinkController;
use Modules\Referrals\Http\Controllers\MyReferralClickController;

Route::post('referral-clicks', [ReferralClickController::class, 'store']);
Route::post('referral-clicks/complete', [ReferralClickController::class, 'markAsCompleted']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('referrers', [ReferrerController::class, 'index']);
    Route::get('referral-actions', [ReferralActionController::class, 'index']);
    Route::get('my/referral-links', [MyReferralLinkController::class, 'index']);
    Route::get('my/referral-clicks', [MyReferralClickController::class, 'index']);
});
