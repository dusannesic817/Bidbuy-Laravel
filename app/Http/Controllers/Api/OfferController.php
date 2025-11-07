<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer;
use App\Http\Resources\OfferResource;
use App\Notifications\AuctionActionNotification;


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
            return $this->errorMessage('You cannot place a bid on your own auction', 422);
        }

        if ($auction->expiry_time && now()->greaterThan($auction->expiry_time)) {
           return $this->errorMessage('The auction has already expired.', 422);
        }

        $currentPrice = $auction->highestOffer->price ?? $auction->started_price;

        if ($request->price < $currentPrice + 20) {
           return $this->errorMessage('Offer must be at least: ' . ($currentPrice + 20), 422);
        }

        Offer::create([
            'auction_id' => $id,
            'user_id'    => Auth::id(),
            'price'      => $request->price,
            'status'     => 'Pending', 
        ]);

        $auction->load('highestOffer');
        $auction->user->notify(new AuctionActionNotification($auction, Auth::user(), 'bid'));

        return $this->successMessage('Your offer has been placed successfully', ['current_price' => $request->price]);

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


    public function patch (Request $request, Offer $offer){
        $highestOffer = Offer::with('auction')->where('auction_id', $offer->auction_id)
            ->orderByDesc('price')
            ->first();

        if($highestOffer->auction->user_id != Auth::id()){
           return $this->errorMessage('You do not have permission to manage offers for this auction.', 403);
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

        $bidder = User::find($highestOffer->user_id);
        $auction = Auction::find($highestOffer->auction_id);
        $bidder->notify(new AuctionActionNotification($auction, Auth::user(), strtolower($validated['status'])));
        
        return $this->successMessage("The offer has been {$validated['status']}.");

    }


   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
