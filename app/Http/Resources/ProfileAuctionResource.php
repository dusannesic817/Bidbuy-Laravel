<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileAuctionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
     public function toArray(Request $request): array
    {
        $all = $this->auctions->values();
        $active = $this->auctions->filter(fn($a) => $a->status === 1)->values();
        $expired = $this->auctions->filter(fn($a) => $a->status === 0)->values();

        $mapAuction = fn($a) => [
            'id' => $a->id,
            'name' => $a->name,
            'price' => $a->highestOffer->price ?? $a->started_price,
            'short_description' => $a->short_description,
            'image' => optional($a->images->first())->url,
            'expired_time' => $a->expiry_time,
            'is_expired' => $a->expiry_time <= now(),
        ];

        return [
            'all_auctions' => $all->map($mapAuction),
            'active_auctions' => $active->map($mapAuction),
            'expired_auctions' => $expired->map($mapAuction),
        ];
    }


}
