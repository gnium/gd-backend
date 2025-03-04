<?php

namespace Modules\Referrals\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Referrals\Models\ReferralAction;
use Illuminate\Routing\Controller;

class ReferralActionController extends Controller
{   
    public function index(Request $request)
    {
        $query = ReferralAction::query();

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
