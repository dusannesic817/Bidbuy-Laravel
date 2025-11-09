<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Offer;
use App\Models\Auction;
use App\Http\Resources\ReviewResource;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
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
    public function store(Request $request, Auction $auction)
    {


        $data = $request->validate([
            'mark' => ['required', 'in:0,1'],
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $offer = Offer::where('auction_id', $auction->id)
            ->where('status', 'Accepted')
            ->firstOrFail();

       
        $alreadyReviewed = Review::where('auction_id', $auction->id)
            ->where('reviewer_id', $userId)
            ->exists();

        if ($alreadyReviewed) {
            return $this->errorMessage('Already reviewed.', 422);
        }
        
        if ($userId === $auction->user_id) {            
            $userToReview = $offer->user_id;
        } else {           
            $userToReview = $auction->user_id;
        }

        $review = Review::create([
            'auction_id'  => $auction->id,
            'offer_id'    => $offer->id,
            'user_id'     => $userToReview,
            'reviewer_id' => $userId,
            'mark'        => $data['mark'],
        ]);

       
        return $this->successMessage('Review submitted successfully.', ['data' => $review]);
    }


    /**
     * Display the specified resource.
     */


    //prepraviti na auth
    public function myReviews()
    {
       
        $role = request('role');
        
        $reviews = Review::with(['user','reviewer','auction'])
            ->where('user_id', Auth::id())
            ->orWhere('reviewer_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);


        if ($role === 'seller') {
            $reviews = $reviews->where('user_id', Auth::id())->values();
        } elseif ($role === 'buyer') {
            $reviews = $reviews->where('reviewer_id', Auth::id())->values();
        }

       
        $totalPositive = $reviews->where('mark', 1)->count();
        $totalNegative = $reviews->where('mark', 0)->count();
        $total = $reviews->count();

       
        return response()->json([
            'success' => true,
            'data'  => ReviewResource::collection($reviews),
            'stats' => [
                'total_positive' => $totalPositive,
                'total_negative' => $totalNegative,
                'total_reviews'  => $total,
            ]
        ]);
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
