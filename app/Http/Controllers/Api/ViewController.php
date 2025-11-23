<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\View;
use App\Models\Auction;
use Illuminate\Http\Request;
use App\Http\Resources\AuctionResource;

class ViewController extends Controller
{
    public function count($id){
        return View::where('auction_id',$id)->count();
    }

}