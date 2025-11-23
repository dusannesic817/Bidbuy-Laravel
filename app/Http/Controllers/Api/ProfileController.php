<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FollowedAuctionsResource;
use App\Http\Resources\ProfileAuctionResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function completeProfile(Request $request)
    {
        $validated = $request->validate([
                'username' => ['required', 'string', 'max:50', 'unique:users,password'],
                'address' => ['required', 'string', 'max:255', 'unique:users,address'],
                'number' => ['required', 'string', 'max:20', 'unique:users,number'],
        ]);

        $user = Auth::user();
        $user->update($validated);

        return response()->json([
            'message' => 'Profile completed successfully',
            'user' => $user
        ]);
    }

    /**
     * Display the specified resource.
     */

    //prepraviti na auth
    public function myProfile()
    {
         return  User::findOrFail(intval(Auth::id()));
       
    }


    //prepraviti na auth
    public function myAuctions()
    {

        $status = request('status'); 
        $query = Auth::user()->auctions()->with(['images', 'highestOffer'])->orderByDesc('created_at');

        if ($status === 'active') {
            $query->where('status', 1);
        } elseif ($status === 'expired') {
            $query->where('status', 0);
        }

        $my_auctions = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => ProfileAuctionResource::collection($my_auctions),
        ]);

    }

    public function followedAuctions()
    {
        $user = User::findOrFail(intval(Auth::id()));
        $followed= $user->followedAuctions()->with('highestOffer','images')->paginate(10);
        return FollowedAuctionsResource::collection($followed);

    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        if ($user->id != $id) {
            return $this->errorMessage('You dont have permission', 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'min:3', 'max:50'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'address' => ['required','string','max:255'],
            'number' => ['required','string','max:20'],
        ]);

        
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return response()->json(['message' => 'Profile updated.'], 200);
    }
}
