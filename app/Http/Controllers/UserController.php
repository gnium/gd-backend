<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
    
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company' => $request->company,
            'deal_stage' => $request->deal_stage,
            'publisher_id' => $request->publisher_id,
        ]);

        // Assign role to user
        $role = \Modules\Roles\Models\Role::where('name', $request->role)->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        // Load the roles relationship for the response
        $user->load('roles');

        return response()->json(['data' => $user], 201);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'company' => 'nullable|string',
            'website' => 'nullable|string',
            'deal_stage' => 'nullable|string',
            'publisher_id' => 'sometimes|required|integer',
            'is_active' => 'sometimes|required|boolean'
        ]);

        $user->update($request->only(['name', 'email', 'company', 'website', 'deal_stage', 'publisher_id', 'is_active']));
        return response()->json(['data' => $user]);
    }

    public function updateByHubspot(Request $request)
    {
        
        if ($request->header('X-HubSpot-Token') !== config('services.hubspot.token')) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        return response()->json(['data'=>$request->header('X-HubSpot-Token')]);

        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'company' => 'required|string',
            'deal_stage' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update([
            'name' => $request->name,
            'company' => $request->company,
            'deal_stage' => $request->deal_stage,
        ]);
        return response()->json(['data' => $user]);
    }

    public function updateMyUser(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'company' => 'nullable|string',
            'website' => 'nullable|string',
            'deal_stage' => 'nullable|string',
            'publisher_id' => 'sometimes|required|integer'
        ]);

        $user->update($request->only(['name', 'email', 'company', 'website', 'deal_stage', 'publisher_id']));

        return response()->json(['data' => $user]);
    }
}
