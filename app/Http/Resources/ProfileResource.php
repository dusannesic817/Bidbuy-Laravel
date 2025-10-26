<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
     return [
            "id" => $this->id,

            "active_auctions" => $this->whenLoaded('auctions', function () {
                return $this->auctions->map(function ($auction) {
                    return [
                        'id' => $auction->id,
                        'name' => $auction->name,
                        'short_description' => $auction->short_description,
                        'current_price' => $auction->highestOffer->price ?? $auction->started_price,                       
                        'expiry_time' => $auction->expiry_time,
                        'status' => $auction->status,
                        'image' => optional($auction->images->first())->url,                                                                  
                    ];
                });
            }),
        ];
    }
}
