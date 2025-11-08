<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileAuctionResource extends JsonResource
{
   public function toArray($request)
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'price' => $this->highestOffer->price ?? $this->started_price,
        'short_description' => $this->short_description,
        'image' => optional($this->images->first())->img_path,
        'expired_time' => $this->expiry_time,
        'is_expired' => $this->expiry_time <= now(),
    ];
}
}
