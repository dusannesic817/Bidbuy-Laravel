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
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'username' => ['required', 'string', 'max:50', 'unique:users,password'],
            'address' => ['required', 'string', 'max:255', 'unique:users,address'],
            'number' => ['required', 'string', 'max:20', 'unique:users,number'],

        ]);

        // Kreiranje korisnika
        $user = User::create([
            'name'     =>   $request->name,
            'surname'  =>   $request->surname,
            'email'    =>   $request->email,
            'password' =>   Hash::make($request->password),
            'username' =>   $request->username,
            'address'  =>   $request->address,
            'number'   =>   $request->number,
            'is_active' => 0

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
