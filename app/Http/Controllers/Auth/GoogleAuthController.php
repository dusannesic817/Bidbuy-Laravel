<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
       /** @var GoogleProvider $provider */
            $provider = Socialite::driver('google');
            return $provider->stateless()->redirect();


    }

    public function callback(Request $request)
    {
        /** @var GoogleProvider $provider */
        $provider = Socialite::driver('google');
        $googleUser = $provider->stateless()->user();

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(uniqid()),
            ]
        );

        Auth::login($user);

        $token = $user->createToken('google-token')->plainTextToken;

        return response()->json([
            'message' => 'Google login successful',
            'token' => $token,
            'user' => $user,
        ]);

        //return redirect("http://localhost:3000/auth-success?token=$token");
    }
}