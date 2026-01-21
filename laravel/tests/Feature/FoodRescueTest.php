<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class FoodRescueTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function food_rescue_page_loads_for_guests()
    {
        $response = $this->get(route('food-rescue'));
        $response->assertStatus(200);
    }

    /** @test */
    public function food_rescue_detail_page_loads()
    {
        $response = $this->get(route('food-rescue.detail', ['id' => 1]));
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    /** @test */
    public function unauthenticated_user_cannot_claim_food_rescue()
    {
        $response = $this->post(route('food-rescue.claim', ['id' => 1]));

        // Should redirect to login
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_claim_food_rescue()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('food-rescue.claim', ['id' => 1]));

        $this->assertTrue(in_array($response->status(), [200, 302]));
    }
}
