<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auction;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\View>
 */
class ViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $auction_id = Auction::pluck("id")->random() ?? fake()->numberBetween(1, 10);
        return [
           'auction_id' => $auction_id,
           'ip_address' => $this->faker->ipv4(),
           'user_agent' => $this->faker->userAgent(),
        ];
    }
}
