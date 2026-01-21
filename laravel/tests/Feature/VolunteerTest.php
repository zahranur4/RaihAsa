<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class VolunteerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function volunteer_page_loads()
    {
        $response = $this->get(route('volunteer'));
        $response->assertStatus(200);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_volunteer_register()
    {
        $response = $this->get(route('volunteer.register'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_access_volunteer_register()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('volunteer.register'));

        $response->assertStatus(200);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_volunteer_dashboard()
    {
        $response = $this->get(route('volunteer.dashboard'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_access_volunteer_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('volunteer.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_check_volunteer_status()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('volunteer.status'));

        $response->assertStatus(200);
    }
}
