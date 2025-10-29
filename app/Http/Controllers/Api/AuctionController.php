<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\User;
use App\Models\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AuctionResource;
use App\Services\ViewService;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auction = Auction::with(['highestOffer'])->paginate(30);
       
        return AuctionResource::collection($auction);
    }


    public function store(Request $request)
    {
    
        $validated = $request->validate([
            'category_id'       => ['required', 'exists:categories,id'],
            'name'              => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:200'],
            'description'       => ['required', 'string'],
            'started_price'     => ['required', 'numeric', 'min:10'],
            'expiry_time'       => ['required', 'date', 'after:now'],
        ]);

    
        $auctionData = [
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'started_price' => $validated['started_price'],
            'expiry_time' => $validated['expiry_time'],
            'user_id'     => Auth::id(), 
            'status'      => 1,          
        ];

      
        Auction::create($auctionData);
      
        return response()->json([
            'success' => true,
            'message' => 'Auction successfully created!',
            
        ], 201);
    }


    public function show(Request $request, string $id, ViewService $viewService)
    {

        $auction = Auction::with([
            'user.reviews',
            'highestOffer'
        ])->findOrFail($id);
        
        $viewService->trackAuctionView($request, $auction->id);
        //$this->view($request,$auction);
        return new AuctionResource($auction);
    }


    public function auctionOffers(Auction $auction)
    {
       
        if ($auction->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You do not have permission to access this page.'
            ], 403);
        }

        $offers = $auction->offers()->with('user')->get();

        return response()->json([
            'auction_id' => $auction->id,
            'offers' => $offers
        ]);
    }

    
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

        $auctionData = [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'started_price' => $validated['started_price'],
            'expiry_time' => $validated['expiry_time'],
        ];
        $auction->update($auctionData);

        return response()->json([
            'success' => true,
            'message' => 'Successfully changed data!',
        ], 200);
    }

    public function followAuction($auctionId) { 
        $user = Auth::user();
        $user->followedAuctions()->syncWithoutDetaching([$auctionId]); 
        return response()->json([ 'success' => true, 'message' => 'Auction followed.' ]); 
    }


     public function unfollowAuction($auctionId) {
   
        $user = Auth::user();
        $user->followedAuctions()->detach($auctionId); 
        return response()->json(data: [  
            'success' => true, 
            'message' => 'Auction unfollowed.' 
        ]);
     }
 
    public function destroy(string $id)
    {
        return Auction::destroy($id);
    }

   public function search(Request $request)
{
    $queryString = $request->input('q'); // search
    $condition = $request->input('condition'); 
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');

    $auctions = Auction::with(['user', 'highestOffer'])
        ->when($queryString, function ($query) use ($queryString) {
            $query->where(function ($q) use ($queryString) {
                $q->where('name', 'like', "%{$queryString}%")
                  ->orWhere('short_description', 'like', "%{$queryString}%");
            });
        })
        ->when($condition, function ($query) use ($condition) {
            $query->where('condition', $condition);
        })
        ->when($minPrice, function ($query) use ($minPrice) {
            $query->where('starting_price', '>=', $minPrice);
        })
        ->when($maxPrice, function ($query) use ($maxPrice) {
            $query->where('starting_price', '<=', $maxPrice);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return AuctionResource::collection($auctions);
}



}
