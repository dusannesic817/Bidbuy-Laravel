<?php

namespace Database\Factories;

use App\Models\Auction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $user_id = User::pluck("id")->random() ?? fake()->numberBetween(1, 10);
         $auction_id = Auction::pluck("id")->random() ?? fake()->numberBetween(1, 10);
        return [
            "auction_id"=> $auction_id,
            'user_id' => $user_id,
            'reviewer_id' => $user_id,
            'mark'=> fake()->boolean()

        ];
    }
}
