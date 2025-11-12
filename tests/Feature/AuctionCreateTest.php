<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class AuctionCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_auction(){
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $payload = [
            'name' => 'Test Aukcija',
            'short_description' => 'Kratak opis',
            'description' => 'Detaljan opis aukcije',
            'started_price' => 456,
            'condition' => 'Novo',
            'expiry_time' => now()->addDays(10)->toDateTimeString(),
            'status' => true,
            'category_id' => $category->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/auctions', $payload);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Test Aukcija']);

        $this->assertDatabaseHas('auctions', [
            'name' => 'Test Aukcija',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
    }
    public function test_auction_creation_fails_when_required_fields_are_missing(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/auctions', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'category_id',
        'name',
        'short_description',
        'description',
        'started_price',
        'expiry_time',
    ]);
}
}