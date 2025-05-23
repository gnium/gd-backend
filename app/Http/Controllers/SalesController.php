<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
            'event_date' => 'required|date',
            'order_id' => 'required|string|unique:sales',
            'website' => 'required|string',
            'publisher_name' => 'required|string',
            'publisher_id' => 'required|integer',
            'sale_amount' => 'required|numeric|min:0',
            'commission_amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        
        // Check if user has permission to create sale for this publisher_id
        if (!$user->hasRole('admin') && $user->publisher_id !== $request->publisher_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sale = Sale::create([
            ...$request->all(),
            'user_id' => $user->id
        ]);

        return response()->json(['data' => $sale], 201);
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'country' => 'sometimes|required|string',
            'event_date' => 'sometimes|required|date',
            'order_id' => 'sometimes|required|string|unique:sales,order_id,' . $sale->id,
            'website' => 'sometimes|required|string',
            'publisher_name' => 'sometimes|required|string',
            'publisher_id' => 'sometimes|required|integer',
            'sale_amount' => 'sometimes|required|numeric|min:0',
            'commission_amount' => 'sometimes|required|numeric|min:0',
        ]);

        $user = Auth::user();
        
        // Check if user has permission to update this sale
        if (!$user->hasRole('admin') && $user->publisher_id !== $sale->publisher_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sale->update($request->all());

        return response()->json(['data' => $sale]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Sale::query();

        // If not admin, only show sales for user's publisher_id
        if (!$user->hasRole('admin')) {
            $query->where('publisher_id', $user->publisher_id);
        }

        // Add filters if provided
        if ($request->has('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }

        if ($request->has('start_date')) {
            $query->where('event_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('event_date', '<=', $request->end_date);
        }

        return response()->json(['data' => $query->get()]);
    }

    public function show(Sale $sale)
    {
        $user = Auth::user();
        
        // Check if user has permission to view this sale
        if (!$user->hasRole('admin') && $user->publisher_id !== $sale->publisher_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['data' => $sale]);
    }
}
