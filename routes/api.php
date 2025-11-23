<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuctionController,
    CategoryController,
    ChatRoomController,
    OfferController,
    ProfileController,
    ReviewController,
    UserController,
    ViewController,
    ImageController,
    MessageController
};

require __DIR__.'/auth.php';

    //Categories and Subcategories
    Route::get("/categories",           [CategoryController::class,"index"])->name("categories.index");
    Route::get("/categories/{id}",      [CategoryController::class,"show"])->name("categories.show");
    Route::get("/subcategories/{id}",   [CategoryController::class,"subcategory"])->name("categories.subcategory");

    //Search and Filters
    Route::get('/auctions/search', [AuctionController::class, 'search'])->name('auctions.search');

    //Auctions
    Route::get("/auctions",      [AuctionController::class,"index"])->name("auctions.index");
    Route::get("/auctions/{id}", [AuctionController::class,"show"])->name("auctions.show");

    //Users
    Route::get("/users/{id}",           [UserController::class,"show"])->name("users.show");
    Route::get("/users/{id}/auctions",  [UserController::class,"userAuctions"])->name("users.auctions");

    //Offers
    Route::get("/offers", [OfferController::class,"index"])->name("offers.index");

    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        return $request->user();
    });

Route::middleware(['auth:sanctum'])->group(function () {
    //Auctions
    Route::post("/auctions",                 [AuctionController::class,"store"])->name("auctions.store")->middleware('profile.complete');
    Route::post("/auctions/{id}/follow",     [AuctionController::class,"followAuction"])->name("auctions.followAuction");
    Route::put("/auctions/{id}",             [AuctionController::class,"update"])->name("auctions.update")->middleware('profile.complete');
    Route::delete("/auctions/{id}",          [AuctionController::class,"destroy"])->name("auctions.destroy");
    Route::delete("/auctions/{id}/unfollow", [AuctionController::class,"unfollowAuction"])->name("auctions.unfollowAuction");
    Route::get('/auctions/{auction}/offers', [AuctionController::class, 'auctionOffers'])->name('auctions.offers')->middleware('profile.complete');


    //Offer
    Route::post('/auctions/{id}/offer', [OfferController::class, 'store'])->middleware('throttle:5,1')->name('offers.store');
    Route::get("/offers/my-offers",     [OfferController::class,"myOffers"])->name("offers.myOffers");
    Route::patch('/offers/{auction}',     [OfferController::class, 'patch'])->name('offers.patch');

    //Profile
    Route::put("/profiles/{id}",       [ProfileController::class,"update"]);
    Route::post("/profiles/completeProfile",       [ProfileController::class,"completeProfile"]);
    Route::get("/profiles/my-profile",        [ProfileController::class,"myProfile"])->name("profiles.myProfile");
    Route::delete("/profiles/{id}",           [ProfileController::class,"destroy"])->name("profiles.destroy");
    Route::put("/profiles/{id}",              [ProfileController::class,"update"])->name("profiles.update");
    Route::get('/profiles/my-auctions',       [ProfileController::class, 'myAuctions'])->name('profiles.myAuctions');
    Route::get('/profiles/followed-auctions', [ProfileController::class, 'followedAuctions'])->name('profiles.followedAuctions');

    //Review
    Route::get("/reviews/my-reviews",           [ReviewController::class,"myReviews"])->name("reviews.myReviews");
    Route::post("/auctions/{auction}/reviews",  [ReviewController::class,"store"])->name("reviews.store");

    //Views
    Route::get("/views/{id}", [ViewController::class,"count"])->name("views.count");

    //Images
    Route::post("/auctions/{id}/images",        [ImageController::class,"store"])->name("images.store");
    Route::delete("/images/{id}",               [ImageController::class,"destroy"])->name("images.destroy");
    Route::delete("/auctions/{auction}/images", [ImageController::class, "destroyAll"])->name("images.destroyAll");

    //Message
    Route::post('/chat-rooms/{chatRoom}/messages', [MessageController::class, 'store']);
    Route::get( '/chat-rooms/{chatRoom}/messages', [MessageController::class, 'show']);
    Route::get( '/chat-rooms',                     [MessageController::class, 'myMessages']);
    Route::put('/messages/{message}/seen',        [MessageController::class,'markAsRead']);


    //ChatRoom
    Route::post('/chat-rooms/find-or-create', [ChatRoomController::class,"findOrCreate"]);
    
});



