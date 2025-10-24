<?php

use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OfferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';


Route::get("/categories", [CategoryController::class,"index"])->name("categories.index");
//Route::get("/offers", [OfferController::class,"index"])->name("offers.index");
Route::get('/auctions/search', [AuctionController::class, 'search'])->name('auctions.search');


//Auctions
Route::get("/auctions", [AuctionController::class,"index"])->name("auctions.index");
Route::get("/auctions/{id}", [AuctionController::class,"show"])->name("auctions.show");

//Users
Route::get("/users/{id}", [UserController::class,"show"])->name("users.show");



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {

    //Auctions
    Route::post("/auctions", [AuctionController::class,"store"])->name("auctions.store");
    Route::put("/auctions/{id}", [AuctionController::class,"update"])->name("auctions.update");
    Route::delete("/auctions/{id}", [AuctionController::class,"destroy"])->name("auctions.destroy");

    //Offer
    Route::post('/auctions/{id}/offer', [OfferController::class, 'store'])->name('offers.store');
    
});

