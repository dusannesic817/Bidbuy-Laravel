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
            return response()->json([
                'success' => false,
                'message' => 'Already reviewed.'
            ], 422);
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

        return response()->json([
            'success' => true,
            'message' => 'Successfully reviewed',
            'data' => $review
        ], 201);
    }


    /**
     * Display the specified resource.
     */

    //prepraviti na auth
    public function myReviews()
    {
        $reviews = Review::with( 'reviewer')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $positiveCount = $reviews->getCollection()->where('mark', operator: 1)->count();
        $negativeCount = $reviews->getCollection()->where('mark', operator: 0)->count();

        return ReviewResource::collection($reviews)
            ->additional([
                'positive_count' => $positiveCount,
                'negative_count' => $negativeCount,               
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
