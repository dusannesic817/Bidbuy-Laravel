<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auction = Auction::with('highestOffer')->find(1);
       

        $najveca = $auction->highestOffer->price ?? $auction->started_price;
        return $auction;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $data = $request->validate([
            'price' => 'required|numeric|min:1',
        ]);

        $data['auction_id'] = $id;
        $data['user_id'] = Auth::id();

        $auction = Auction::with('highestOffer')->findOrFail($id);
        $currentPrice = $auction->highestOffer->price ?? $auction->started_price;

        if($auction->user_id === Auth::id()){
            return response()->json([
                'message' => "You cannot place a bid on your own auction"
            ], 422);
        }

        if ($data['price'] < $currentPrice + 20) {
            return response()->json([
                'message' => "Offer must be minimum: " . ($currentPrice + 20),
            ], 422);
        }

        Offer::create($data);

        return response()->json([
            'message' => 'Your offer has been successfully created',
            'current_price' => $data['price'],
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

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
