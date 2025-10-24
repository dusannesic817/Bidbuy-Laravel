<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AuctionResource;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:200'],
            'description'   => ['required', 'string'],
            'started_price' => ['required', 'numeric', 'min:10'],
            'expiry_time'   => ['required', 'date', 'after:now'],

        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 1;

        Auction::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Auction successfully created!',
        ], 201);
    }

    public function show(string $id)
    {

        $auction = Auction::with([
            'category.subcategories',
            'user.reviews',
            'highestOffer'
        ])->findOrFail($id);

        // return $auction;

        return new AuctionResource($auction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $auction = Auction::findOrFail($id);

        if ($auction->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to edit this auction.',
            ], 403);
        }

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'started_price' => ['required', 'numeric', 'min:10'],
            'expiry_time' => ['required', 'date', 'after:now'],
        ]);

        $auction->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Successfully changed data!',
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Auction::destroy($id);
    }

    public function search(Request $request) { 
        $query = $request->input('q', ''); 
        $auctions = Auction::with(['user', 'highestOffer']) 
            ->where('name', 'like', "%{$query}%") 
            ->orWhere('short_description', 'like', "%{$query}%") 
            ->paginate(10); 

        return AuctionResource::collection($auctions);
    }

}
