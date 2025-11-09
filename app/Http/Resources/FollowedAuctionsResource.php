<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowedAuctionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
{
    return [
        'id' => $this->id,
        'title' => $this->name,
        'short_description' => $this->short_description,
        'expired_time' =>$this->expiry_time,
        'highest_offer' => $this->highestOffer ? $this->highestOffer->price : null,
        'image' => $this->images->isNotEmpty()
            ? asset('storage/' . $this->images->first()->img_path)
            : asset('storage/images/default.jpg'),
    ];
}

}
