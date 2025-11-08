<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'mark' => $this->mark,
            'created_at' => $this->created_at->toDateTimeString(),
            
            'reviewer' => [
                'id' => $this->reviewer->id ?? null,
                'name' => $this->reviewer->name ?? null,
                'surname' => $this->reviewer->surname ?? null,
                'email' => $this->reviewer->email ?? null,
            ],

            'auction' => [
                'id' => $this->auction->id ?? null,
                'name' => $this->auction->name ?? null,
            ],  
        ];
    }
}
