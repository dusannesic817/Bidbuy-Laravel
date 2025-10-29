<?php

use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SubcategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

    //Categories and Subcategories
    Route::get("/categories", [CategoryController::class,"index"])->name("categories.index");
    Route::get("/categories/{id}", [CategoryController::class,"show"])->name("categories.show");
    Route::get("/subcategories/{id}", [CategoryController::class,"subcategory"])->name("categories.subcategory");

    //Search and Filters
    Route::get('/auctions/search', [AuctionController::class, 'search'])->name('auctions.search');

    //Auctions
    Route::get("/auctions", [AuctionController::class,"index"])->name("auctions.index");
    Route::get("/auctions/{id}", [AuctionController::class,"show"])->name("auctions.show");

    //Users
    Route::get("/users/{id}", [UserController::class,"show"])->name("users.show");



    /*Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        return $request->user();
    });*/

Route::middleware(['auth:sanctum'])->group(function () {
    //Auctions
    Route::post("/auctions", [AuctionController::class,"store"])->name("auctions.store");
    Route::put("/auctions/{id}", [AuctionController::class,"update"])->name("auctions.update");
    Route::delete("/auctions/{id}", [AuctionController::class,"destroy"])->name("auctions.destroy");
    Route::get('/auctions/{auction}/offers', [AuctionController::class, 'auctionOffers'])->name('auctions.offers');

    //Offer
    Route::post('/auctions/{id}/offer', [OfferController::class, 'store'])->name('offers.store');
    Route::get("/offers/my-offers", [OfferController::class,"myOffers"])->name("offers.myOffers");

    //Profile
    Route::get("/profiles/my-profile", [ProfileController::class,"myProfile"])->name("profiles.myProfile");
    Route::delete("/profiles/{id}", [ProfileController::class,"destroy"])->name("profiles.destroy");
    Route::put("/profiles/{id}", [ProfileController::class,"update"])->name("profiles.update");
    Route::get('/profiles/my-auctions', [ProfileController::class, 'myAuctions'])->name('profiles.myAuctions');
    Route::get('/profiles/followed-auctions', [ProfileController::class, 'followedAuctions'])->name('profiles.followedAuctions');

    //Review
    Route::get("/reviews/my-reviews", [ReviewController::class,"myReviews"])->name("reviews.myReviews");
    Route::post('/auctions/{auction}/review', [ReviewController::class, 'store']);
    


    
});


