<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer;
use App\Http\Resources\OfferResource;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auction = Auction::with('highestOffer')->find(1);
        return $auction;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:1',
        ]);

        $auction = Auction::with('highestOffer')->findOrFail($id);

        if ($auction->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => "You cannot place a bid on your own auction"
            ], 422);
        }

        if ($auction->expiry_time && now()->greaterThan($auction->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => "This auction has already ended"
            ], 422);
        }

        $currentPrice = $auction->highestOffer->price ?? $auction->started_price;

        if ($request->price < $currentPrice + 20) {
            return response()->json([
                'success' => false,
                'message' => "Offer must be at least: " . ($currentPrice + 20)
            ], 422);
        }

        Offer::create([
            'auction_id' => $id,
            'user_id'    => Auth::id(),
            'price'      => $request->price,
            'status'     => 'Pending', 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your offer has been placed successfully',
            'current_price' => $request->price,
        ], 201);
    }




    /**
     * Display the specified resource.
     */

    //prepraviti na auth
    public function myOffers()
    {

        $active = Offer::with('auction.images')
            ->where('user_id',Auth::id())
            ->whereHas('auction', fn($q) => $q->where('status', 1))
            ->latest()
            ->get();

        $expired = Offer::with('auction.images')
            ->where('user_id', Auth::id())
            ->whereHas('auction', fn($q) => $q->where('status', 0))
            ->latest()
            ->get();


        return response()->json([
            'active' => OfferResource::collection($active),
            'expired' => OfferResource::collection($expired),
        ]);
    }

    //moze videti sve ponude koje je dobio za neku Aukciju
   

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
