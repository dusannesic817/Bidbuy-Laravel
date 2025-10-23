<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // Validacija
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Pokušaj autentifikacije
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user(); 

        $token = $user->createToken(
            'api-token',
            ['*'],
            Carbon::now()->addHours(2) 
        )->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

     
    public function destroy(Request $request)
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        Auth::logout();

        return response()->json(['message' => 'Logged out']);
    }
}
