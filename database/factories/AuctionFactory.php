<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;
use function Illuminate\Support\enum_value;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   

       // $user_id = User::pluck("id")->random() ?? fake()->numberBetween(1, 10);
        //$category_id = Category::pluck("id")->random() ?? fake()->numberBetween(1, 10);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => ucfirst(fake()->words(2, true)),
            'short_description' => fake()->text(50),
            'description' => fake()->text(300),
            'started_price' =>fake()->numberBetween(10,6000),
            'condition' => fake()->randomElement(['Polovno','Novo','Kao Novo']),
            'expiry_time' => fake()->dateTimeBetween('now', '+30 days'),
            'status' =>fake()->boolean()
        ];
    }
}
