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
 
    public function index()
    {
        return Auction::with('highestOffer')->find(1);
       
    }


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

        if ($auction->expiry_time && now()->greaterThan($auction->expiry_time)) {
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


   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {

        $highestOffer = Offer::with('auction')->where('auction_id', $offer->auction_id)
            ->orderByDesc('price')
            ->first();

        if($highestOffer->auction->user_id != Auth::id()){
            return response()->json([
                'success'=>false,
                'message' => "You do not have permission to edit this auction.",
            ]);
        }

        $validated = $request->validate([
            'status' => 'required|in:Accepted,Rejected',
        ]);

        $highestOffer->update([
            'status' => $validated['status'],
        ]);

        if ($validated['status']) {
            $highestOffer->auction->update([
                'status' => 0,               
            ]);
        }

        return response()->json([
            'success'=>true,
            'message' => "The offer has been {$validated['status']}.",
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
