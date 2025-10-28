<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileAuctionResource;
use Illuminate\Http\Request;
use App\Models\User;
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
      
        $profile = User::with([
            'auctions' => function ($q) {
                $q->where('status', 1);
            }, 
            'expiredAuctions' => function ($q) {
                $q->where('status', 0);
            }, 
            'auctions.images',
            'auctions.highestOffer'
        ])->findOrFail(Auth::id());
        
        return new ProfileAuctionResource($profile);
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
