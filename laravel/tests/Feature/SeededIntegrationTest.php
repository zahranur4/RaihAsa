<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeededIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed database untuk setiap test
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Full integration tests dengan seeded data
     */
    public function test_donation_matching_with_real_data()
    {
        $response = $this->get('/donation-matching');
        $this->assertNotNull($response);
        $this->assertTrue($response->status() < 400);
    }

    public function test_wishlist_with_real_data()
    {
        $response = $this->get('/wishlist');
        $this->assertNotNull($response);
        $this->assertTrue($response->status() < 400);
    }

    public function test_food_rescue_with_real_data()
    {
        $response = $this->get('/food-rescue');
        $this->assertNotNull($response);
        $this->assertTrue($response->status() < 400);
    }

    public function test_volunteer_with_real_data()
    {
        $response = $this->get('/volunteer');
        $this->assertNotNull($response);
        $this->assertTrue($response->status() < 400);
    }

    public function test_my_donations_with_real_data()
    {
        $response = $this->get('/my-donations');
        $this->assertNotNull($response);
        $this->assertTrue($response->status() < 400);
    }

    public function test_home_with_real_data()
    {
        $response = $this->get('/');
        $this->assertNotNull($response);
        $this->assertTrue($response->status() < 400);
    }
}
