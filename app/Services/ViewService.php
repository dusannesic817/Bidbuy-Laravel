<?php

namespace App\Services;

use App\Models\View;
use Illuminate\Http\Request;

class ViewService
{
    public function trackAuctionView(Request $request, int $auctionId): void
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        $alreadyViewed = View::where('auction_id', $auctionId)
            ->where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->exists();

        if (! $alreadyViewed) {
            View::create([
                'auction_id' => $auctionId,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
        }
    }
}