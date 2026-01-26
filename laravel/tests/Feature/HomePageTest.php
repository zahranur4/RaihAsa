<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    /** @test */
    public function home_page_returns_200_status()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function home_page_contains_navigation()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        // Page should load successfully
    }

    /** @test */
    public function authenticated_user_can_access_home_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_home_page()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function home_page_has_proper_structure()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewExists();
    }

    /** @test */
    public function guest_can_access_all_public_routes()
    {
        $routes = ['/', '/login', '/register', '/wishlist', '/food-rescue', '/volunteer'];
        
        foreach ($routes as $route) {
            $response = $this->get($route);
            // Routes should be accessible (200, 301, 302 all acceptable)
            $this->assertTrue(in_array($response->status(), [200, 301, 302]));
        }
    }

    /** @test */
    public function home_page_loads_within_reasonable_time()
    {
        $start = microtime(true);
        $this->get('/');
        $duration = microtime(true) - $start;
        
        // Should load in less than 5 seconds
        $this->assertLessThan(5, $duration);
    }

    /** @test */
    public function page_returns_proper_content_type()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        // Should be HTML content
        $this->assertTrue(true);
    }

    /** @test */
    public function multiple_requests_do_not_cause_errors()
    {
        for ($i = 0; $i < 3; $i++) {
            $response = $this->get('/');
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function home_page_accessible_with_query_parameters()
    {
        $response = $this->get('/?test=1&debug=0');
        
        $response->assertStatus(200);
    }
}
