<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
  // AuctionResource.php
public function toArray($request): array
{

   

    return [
        'id' => $this->id,
        'title' => $this->name,
        'short_description' => $this->short_description,
        'current_price' => $this->highestOffer->price ?? $this->started_price,

        $this->mergeWhen($request->routeIs('auctions.show'), [
            'description' => $this->description,
            'condition' => $this->condition,
            'expiry_time' => $this->expiry_time,
            'status' => $this->status,
            
            
        ]),

        'user' => [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'surname' => $this->user->surname,
            'username' =>$this->user->username,
            'email'=> $this->user->email,
            'number' =>$this->user->number,
            'is_active' =>$this->user->is_active,
            'created_at' =>$this->user->created_at,

            $this->mergeWhen($request->routeIs('auctions.show'), function () {
                $positiveMark = $this->user->reviews->where('mark', 1)->count();
                $negativeMark = $this->user->reviews->where('mark', 0)->count();

                return [
                    'positive_reviews' => $positiveMark,
                    'negative_reviews' => $negativeMark,
                ];
            }),
        ],
       
        $this->mergeWhen($request->routeIs('auctions.show') && $this->relationLoaded('category'), [
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'subcategories' => $this->category->subcategories->map(fn ($sub) => [
                    'id' => $sub->id,
                    'name' => $sub->name,
                ]),
            ],
        ]),

       
        
    ];
}

}
