<?php

namespace Modules\Referrals\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Referrals\Models\ReferralClick;
use Illuminate\Routing\Controller;

class ReferralClickController extends Controller
{   
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'referral_link_id' => 'required|exists:referral_links,id',
    //     ]);
    
    //     $click = ReferralClick::create([
    //         'referral_link_id' => $request->referral_link_id,
    //         'code' => Str::uuid()->toString(), // Código único para rastrear el clic
    //         'ip_address' => $request->ip(),
    //         'user_agent' => $request->header('User-Agent'),
    //         'clicked_at' => now(),
    //     ]);
    
    //     return response()->json([
    //         'message' => 'Click registrado exitosamente.',
    //         'click_code' => $click->code, // Retornar el código único
    //     ], 201);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'referrer_code' => 'required|exists:referrers,code',
            'action_name' => 'required|exists:referral_actions,name',
        ]);

        // Obtener el referrer
        $referrer = \Modules\Referrals\Models\Referrer::where('code', $request->referrer_code)->first();
        if (!$referrer) {
            return response()->json(['message' => 'Referrer not found.'], 404);
        }

        // Obtener la acción
        $action = \Modules\Referrals\Models\ReferralAction::where('name', $request->action_name)->first();
        if (!$action) {
            return response()->json(['message' => 'Referral action not found.'], 404);
        }

        // Obtener el referral link
        $referralLink = \Modules\Referrals\Models\ReferralLink::where('referrer_id', $referrer->id)
            ->where('action_id', $action->id)
            ->first();

        if (!$referralLink) {
            return response()->json(['message' => 'Referral link not found.'], 404);
        }

        // Registrar el click
        $click = \Modules\Referrals\Models\ReferralClick::create([
            'referral_link_id' => $referralLink->id,
            'code' => \Illuminate\Support\Str::uuid()->toString(), // Código único para rastrear el clic
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'clicked_at' => now(),
        ]);

        return response()->json([
            'message' => 'Click registrado exitosamente.',
            'click_code' => $click->code, // Retornar el código único
        ], 201);
    }

    
    public function markAsCompleted(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:referral_clicks,code',
        ]);

        $click = ReferralClick::where('code', $request->code)->first();

        if ($click->action_completed) {
            return response()->json(['message' => 'Esta acción ya estaba marcada como completada.'], 409);
        }

        $click->update([
            'action_completed' => true,
            'completed_at' => now(),
        ]);

        return response()->json(['message' => 'Acción completada exitosamente.'], 200);
    }

}