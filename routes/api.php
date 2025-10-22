<?php

use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuctionControllerController;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';


Route::get("/categories", [CategoryController::class,"index"])->name("categories.index");
Route::get("/auctions", [AuctionController::class,"index"])->name("auctions.index");
Route::get("/auctions/{id}", [AuctionController::class,"show"])->name("auctions.show");



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/test', function (Request $request) {
        return 'text';
    });
});

