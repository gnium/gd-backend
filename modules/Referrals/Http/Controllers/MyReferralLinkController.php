<?php

namespace Modules\Referrals\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Referrals\Models\ReferralLink;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class MyReferralLinkController extends Controller
{   
    public function index(Request $request)
    {
        $referrer = Auth::user()->referrer;
        $query = ReferralLink::where('referrer_id', $referrer->id);

        // Aplicar filtros usando scopes
        if ($request->has('is_active')) {
            $query->active($request->is_active);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        return response()->json(['data' => $query->get()]);
    }

   
}
