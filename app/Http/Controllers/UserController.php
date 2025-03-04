<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query()->with('roles'); // Incluir relaciones

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
