<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RouteAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_page_is_accessible()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
    }

    /** @test */
    public function register_page_is_accessible()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    /** @test */
    public function login_page_is_accessible()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    /** @test */
    public function food_rescue_page_is_accessible()
    {
        $response = $this->get(route('food-rescue'));
        $response->assertStatus(200);
    }

    /** @test */
    public function wishlist_page_is_accessible()
    {
        $response = $this->get(route('wishlist'));
        $response->assertStatus(200);
    }

    /** @test */
    public function volunteer_page_is_accessible()
    {
        $response = $this->get(route('volunteer'));
        $response->assertStatus(200);
    }

    /** @test */
    public function my_donations_page_is_accessible()
    {
        $response = $this->get(route('my-donations'));
        $response->assertStatus(200);
    }

    /** @test */
    public function redirects_register_panti_to_register()
    {
        $response = $this->get('/register-panti');
        $response->assertRedirect(route('register'));
    }

    /** @test */
    public function redirects_admin_to_dashboard()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get('/admin');

        $response->assertRedirect(route('admin.dashboard'));
    }
}
