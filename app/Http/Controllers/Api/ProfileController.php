<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileAuctionResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Offer;


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
    public function show(string $id)
    {
        $profile = User::select('name', 'surname', 'username', 'email', 'password', 'address', 'number')->findOrFail(intval($id));
        return $profile;
    }
    public function myAuctions(string $id)
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
        ])->findOrFail($id);
        
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
