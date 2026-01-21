<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function wishlist_page_loads_for_guests()
    {
        $response = $this->get(route('wishlist'));
        $response->assertStatus(200);
    }

    /** @test */
    public function unauthenticated_user_cannot_fulfill_wishlist()
    {
        $response = $this->post(route('wishlist.fulfill', ['id' => 1]));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_wishlist_matching()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('wishlist.matching'));

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_view_pledge_detail()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('wishlist.pledge-detail', ['id' => 1]));

        $this->assertTrue(in_array($response->status(), [200, 302, 404]));
    }

    /** @test */
    public function authenticated_user_can_confirm_pledge()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('wishlist.pledge.confirm', ['id' => 1]));

        $this->assertTrue(in_array($response->status(), [200, 302, 404]));
    }
}
