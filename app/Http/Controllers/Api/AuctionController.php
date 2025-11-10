<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\User;
use App\Models\Image;
use App\Models\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AuctionResource;
use App\Services\ViewService;
use App\Notifications\AuctionActionNotification;


class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $auction = Auction::with(['highestOffer', 'images'])
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(30);

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
            'images'            => ['nullable', 'array', 'max:6'],
            'images.*'          => ['file', 'image', 'max:5120'],
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

      
        $auction= Auction::create($auctionData);

        if($request->has('images')){
            foreach($request->file('images') as $image){
                $img_name = 'auction-'.$auction->id.'-'.time().rand(1,1000).'.'.$image->extension();
                $imagePath = $image->storeAs('auction_images', $img_name, 'public');
                Image::create([
                    'auction_id' => $auction->id,
                    'img_path' => $imagePath
                ]);
            }
            
           
        }

        return $this->successMessage('Auction successfully created!', ['data' => $auction], 201);
    }


    public function show(Request $request, string $id, ViewService $viewService)
    {

        $auction = Auction::with([
            'user.reviews',
            'highestOffer',
            'images',
        ])->findOrFail($id);
        
        $viewService->trackAuctionView($request, $auction->id);
        return new AuctionResource($auction);
    }


    public function auctionOffers(Auction $auction)
    {
       
        if ($auction->user_id !== Auth::id()) {
           return $this->errorMessage('You do not have permission to view offers for this auction.', 403);
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
           return $this->errorMessage('You do not have permission to edit this auction.', 403);
        }
        
        $validated = $request->validate([
            'category_id'       => ['required', 'exists:categories,id'],
            'name'              => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:200'],
            'description'       => ['required', 'string'],
            'started_price'     => ['required', 'numeric', 'min:10'],
            'expiry_time'       => ['required', 'date', 'after:now'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['file', 'image', 'max:5120'],

        ]);

        $auctionData = [
            'category_id'       => $validated['category_id'],
            'name'              => $validated['name'],
            'short_description' => $validated['short_description'],
            'description'       => $validated['description'],
            'started_price'     => $validated['started_price'],
            'expiry_time'       => $validated['expiry_time'],
        ];
        $auction->update($auctionData);

        return $this->successMessage('Successfully changed data!', ['data' => $auction]);

   
    }

    public function followAuction($auctionId) { 
        $user = Auth::user();
        $user->followedAuctions()->syncWithoutDetaching([$auctionId]);

        $auction = Auction::find($auctionId);
        $auction->user->notify(new AuctionActionNotification($auction, $user, 'followed'));

       return $this->successMessage('Auction has been followed.');
    }


     public function unfollowAuction($auctionId) {
   
        $user = Auth::user();
        $user->followedAuctions()->detach($auctionId); 
        
        return $this->successMessage('Auction has been unfollowed.');
     }
 
    public function destroy(string $id)
    {
        return Auction::destroy($id);

        
    }

   public function search(Request $request)
{
    $queryString = $request->input('q'); 
    $condition   = $request->input('condition'); 
    $minPrice    = $request->input('min_price');
    $maxPrice    = $request->input('max_price');

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
