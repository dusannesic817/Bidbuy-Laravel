<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            
            'auction' => [
                'id' => $this->auction->id,
                'title' => $this->auction->name,
                'short_description'=> $this->auction->short_description,
                'image' => $this->auction->images->first()->img_path ?? null,
                'status' => $this->auction->status,
                'price' => $this->price,
                'expires_at' => $this->auction->expiry_time ?? null,
            ]
        ];
    }
}
