<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->name,

          
            'auctions' => $this->relationLoaded('auctions')
                ? $this->auctions->map(function ($auction) {
                    return [
                        'id' => $auction->id,
                        'title' => $auction->name,
                        'short_description' => $auction->short_description,
                        'current_price' => $auction->highestOffer->price ?? $auction->started_price,
                        
                    ];
                })
                : null,

            
            'subcategories' => $this->relationLoaded('children')
                ? $this->children
                ->filter(fn($child) => $child->relationLoaded('auctions') && $child->auctions->isNotEmpty())
                ->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'auctions' => $child->auctions->map(function ($auction) {
                            return [
                                'id' => $auction->id,
                                'title' => $auction->name,
                                'short_description' => $auction->short_description,
                                'current_price' => $auction->highestOffer->price ?? $auction->started_price,
                                
                            ];
                        }),
                    ];
                })
                : null,
        ];
    }
}
