<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Auction;
use App\Models\User;
use App\Models\Offer;
use App\Models\Category;

class OfferCreateTest extends TestCase
{
    
    use RefreshDatabase;

     /**
      * A basic feature test example.
      */
    public function test_authenticated_user_can_create_offer(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $auction = Auction::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'started_price' => 100,
        ]);
       

        $response = $this->actingAs($user)->postJson("/api/auctions/{$auction->id}/offer", ['price' => 500]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['price' => 500]);

        $this->assertDatabaseHas('offers', [
                'auction_id' => $auction->id,
                'user_id' => $user->id,
                'price' => 500,
                'status' => 'Pending',
        ]);
    }

    public function test_user_cannot_create_offer_on_own_auction(): void
    {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $auction = Auction::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'started_price' => 100,
        ]);

        $response = $this->actingAs($owner)->postJson("/api/auctions/{$auction->id}/offer", ['price' => 500]);
        $response->assertStatus(422);
        $response->assertJsonFragment(['message' => 'You cannot place a bid on your own auction']);

    }
   
}
