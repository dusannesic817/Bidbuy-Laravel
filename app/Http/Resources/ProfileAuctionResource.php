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
        return [
            "active_auctions" => $this->whenLoaded('auctions', function () {
                return $this->auctions->map(fn($a) => [
                    'id' => $a->id,
                    'name' => $a->name,
                    'current_price' => $a->highestOffer->price ?? $a->started_price,
                    'short_description' =>$a->short_description,
                    'image' => optional($a->images->first())->url,
                    'expired_time' => $a->expiry_time,
                ]);
            }),

            "expired_auctions" => $this->whenLoaded('expiredAuctions', function () {
                return $this->expiredAuctions->map(fn($a) => [
                    'id' => $a->id,
                    'name' => $a->name,
                    'current_price' => $a->highestOffer->price ?? $a->started_price,
                    'short_description' =>$a->short_desciption,
                    'image' => optional($a->images->first())->url,
                    'expired_time' => $a->expiry_time,
                ]);
            }),

        ];
    }
}
