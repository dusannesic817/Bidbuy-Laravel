<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Laravel\Sanctum\NewAccessToken;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request for SPA.
     */
    public function store(Request $request)
    {
        // Validacija
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Kreiranje korisnika
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Kreiranje API tokena
        $token = $user->createToken('api-token')->plainTextToken;

        // Vraćanje JSON odgovora
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
