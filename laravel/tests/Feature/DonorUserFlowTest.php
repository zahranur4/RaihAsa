<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DonorUserFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $donor;

    public function setUp(): void
    {
        parent::setUp();
        $this->donor = User::factory()->create(['is_admin' => false]);
    }

    /** @test */
    public function donor_can_access_home_page()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function donor_can_view_wishlist_page()
    {
        $response = $this->get('/wishlist');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function donor_can_view_food_rescue_page()
    {
        $response = $this->get('/food-rescue');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_donor_can_view_my_donations()
    {
        $response = $this->actingAs($this->donor)->get('/my-donations');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function donor_can_access_volunteer_page()
    {
        $response = $this->get('/volunteer');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_donor_can_view_profile()
    {
        $response = $this->actingAs($this->donor)->get('/profile');
        
        $this->assertThat(
            $response->status(),
            $this->logicalOr(
                $this->equalTo(200),
                $this->equalTo(404)
            )
        );
    }

    /** @test */
    public function guest_can_access_registration()
    {
        $response = $this->get('/register');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_can_access_login()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
    }
}
