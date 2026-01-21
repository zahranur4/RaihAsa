<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class MyDonationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function my_donations_page_loads_for_guests()
    {
        $response = $this->get(route('my-donations'));
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_access_my_donations()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('my-donations'));

        $response->assertStatus(200);
    }
}
