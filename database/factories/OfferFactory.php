<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auction;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
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
            "auction_id" => $auction_id,
            "user_id"=> $user_id,
            'price' =>fake()->numberBetween(6100,10000),
            'status' =>fake()->randomElement(['Pending','Accepted','Rejected']),
        ];
    }
}
