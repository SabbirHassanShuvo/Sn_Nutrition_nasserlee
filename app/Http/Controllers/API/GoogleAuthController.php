<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Authenticate or register a user via Google Access Token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'role' => 'nullable|string|in:health_professional,normal_admin', // allow requesting a specific role on registration, default normal user or health pro.
        ]);

        try {
            // Verify Google Token
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->token);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid Google Token', 'message' => $e->getMessage()], 401);
        }

        // Find or create user
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(Str::random(24)),
                'role' => $request->role ?? 'health_professional', // Default to health professional as per context
            ]);
        } else {
            // Update google_id if not set
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        }

        // Generate JWT application token
        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'Successfully authenticated',
            'user' => $user,
            'token' => $token,
        ]);
    }
}
