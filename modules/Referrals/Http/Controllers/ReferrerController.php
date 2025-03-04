<?php

namespace Modules\Referrals\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Referrals\Models\Referrer;
use Illuminate\Routing\Controller;

class ReferrerController extends Controller
{   
    public function index(Request $request)
    {
        $query = Referrer::query()->with('user');

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
