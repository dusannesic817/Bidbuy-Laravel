<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Auction;

class AuctionUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_their_own_auction()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $auction = Auction::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $payload = [
            'category_id' => $category->id,
            'name' => 'Nova Aukcija',
            'short_description' => 'Izmenjen opis',
            'description' => 'Detaljan opis',
            'started_price' => 999,
            'expiry_time' => now()->addDays(5)->toDateTimeString(),
        ];

        $response = $this->actingAs($user)->putJson("/api/auctions/{$auction->id}", $payload);

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Nova Aukcija');

        $this->assertDatabaseHas('auctions', [
            'id' => $auction->id,
            'name' => 'Nova Aukcija',
        ]);
    }

    public function test_user_cannot_update_someone_elses_auction()
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $category = Category::factory()->create();
        $auction = Auction::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
        ]);

        $payload = [
            'category_id' => $category->id,
            'name' => 'Pokušaj izmene',
            'short_description' => 'Ne bi trebalo da prođe',
            'description' => '...',
            'started_price' => 1000,
            'expiry_time' => now()->addDays(5)->toDateTimeString(),
        ];

        $response = $this->actingAs($intruder)->putJson("/api/auctions/{$auction->id}", $payload);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'You do not have permission to edit this auction.',
        ]);
    }
}