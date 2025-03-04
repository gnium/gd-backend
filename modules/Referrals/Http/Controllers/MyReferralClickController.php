<?php

namespace Modules\Referrals\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Referrals\Models\ReferralClick;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class MyReferralClickController extends Controller
{   
    public function index(Request $request)
    {
        $referrer = Auth::user()->referrer;
        $query = ReferralClick::whereHas('referralLink', function ($q) use ($referrer) {
            $q->where('referrer_id', $referrer->id);
        })->with('referralLink');

        // Aplicar filtros usando scopes
        if ($request->has('action_completed')) {
            $query->where('action_completed', $request->action_completed);
        }

        if ($request->has('search')) {
            $query->whereHas('referralLink.action', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('display_name', 'like', "%{$request->search}%");
            });
        }

        return response()->json(['data' => $query->get()]);
    }
}
