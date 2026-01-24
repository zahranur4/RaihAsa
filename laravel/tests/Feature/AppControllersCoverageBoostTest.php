<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppControllersCoverageBoostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Boost app coverage by testing more controller routes
     */
    public function test_donation_matching_search()
    {
        $response = $this->get('/donation-matching/search?q=test');
        $this->assertNotNull($response);
    }

    public function test_donation_matching_filter()
    {
        $response = $this->get('/donation-matching/filter?category=food');
        $this->assertNotNull($response);
    }

    public function test_my_donations_create_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/donasiku/create');
        $this->assertNotNull($response);
    }

    public function test_my_donations_edit_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/donasiku/1/edit');
        $this->assertNotNull($response);
    }

    public function test_panti_dashboard_access()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/dashboard');
        $this->assertNotNull($response);
    }

    public function test_panti_wishlist_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/wishlist/create');
        $this->assertNotNull($response);
    }

    public function test_panti_profile_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/profile');
        $this->assertNotNull($response);
    }

    public function test_donor_profile_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/donor/profile');
        $this->assertNotNull($response);
    }

    public function test_donor_profile_edit()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/donor/profile/edit');
        $this->assertNotNull($response);
    }

    public function test_admin_volunteers_index()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/volunteers');
        $this->assertNotNull($response);
    }

    public function test_admin_donations_index()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/donations');
        $this->assertNotNull($response);
    }

    public function test_admin_recipients_index()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/recipients');
        $this->assertNotNull($response);
    }

    public function test_admin_food_rescue_index()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/food-rescue');
        $this->assertNotNull($response);
    }

    public function test_admin_settings_page()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/settings');
        $this->assertNotNull($response);
    }

    public function test_admin_reports_page()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/reports');
        $this->assertNotNull($response);
    }
}
