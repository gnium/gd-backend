<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Referrals\Services\ReferralService;

class AuthController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    /**
     * Handle user login based on app type.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Check user credentials
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $user->load('roles');
        // Generate token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Register a new user or assign roles based on the app type.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
     
        $user = User::create([
            'name' => explode('@', $request->email)[0],
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $referrerRole = \Modules\Roles\Models\Role::where('name', 'referrer')->first();
        if ($referrerRole) {
            $user->roles()->attach($referrerRole->id);
        }
        
       
        $this->referralService->createReferrerAndLinks($user);
        
        return response()->json(['message' => 'Usuario registrado exitosamente.'], 201);
    }
}