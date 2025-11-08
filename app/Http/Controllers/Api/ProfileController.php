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

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
