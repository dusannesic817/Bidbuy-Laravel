<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $postiveMark = $this->reviews()->where('mark', 1)->count();
        $negativeMark = $this->reviews()->where('mark', 0)->count();

        return [
            
            'id' =>$this->id,
            'name'=>$this->name,
            'surname'=>$this->surname,
            'email' =>$this->email,
            'created_at' =>$this->created_at,
            'username' =>$this->username,
            'address' =>$this->address,
            'number' =>$this->number,
            'is_active' =>$this->is_active,

            'auctions'=>$this->activeAuctions,

            'reviews_summary' => [
                'positive' => $postiveMark,
                'negative' => $negativeMark,
            ],

           


        ];
    }
}
