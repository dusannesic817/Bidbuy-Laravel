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
        $bidder = User::factory()->create();
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $auction = Auction::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'started_price' => 100,
        ]);
       

        $response = $this->actingAs($bidder)->postJson("/api/auctions/{$auction->id}/offer", ['price' => 500]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['price' => 500]);

        $this->assertDatabaseHas('offers', [
                'auction_id' => $auction->id,
                'user_id' => $bidder->id,
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

    public function test_offer_price_must_be_higher_than_current_for_amount(){
        $owner = User::factory()->create();
        $bidder = User::factory()->create();
        $category = Category::factory()->create();
        
        $scenarios = [
            ['started_price' => 80,    'expected_price' => 82],
            ['started_price' => 500,   'expected_price' => 505],
            ['started_price' => 1000,  'expected_price' => 1020],
            ['started_price' => 10000, 'expected_price' => 10050],
        ];

        foreach ($scenarios as $case){
            $auction = Auction::factory()->create([
                'user_id' => $owner->id,
                'category_id' => $category->id,
                'started_price' => $case['started_price'],
            ]);

          $response = $this->actingAs($bidder)->postJson("/api/auctions/{$auction->id}/offer", ['price' => $case['expected_price'] - 1]);
          $response->assertStatus(422);
          $response->assertJsonFragment([
                'message' => 'Offer must be at least: ' . $case['expected_price'],
        ]);


          $validResponse  = $this->actingAs($bidder)->postJson("/api/auctions/{$auction->id}/offer", ['price' => $case['expected_price']]);
          $validResponse ->assertStatus(201);
          $validResponse->assertJsonFragment([
                'price' => $case['expected_price'], 
                'message' => 'Your offer has been placed successfully',
          ]);

        }

    

    }
   
}
