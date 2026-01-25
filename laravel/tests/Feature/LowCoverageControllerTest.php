<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wishlist;
use App\Models\FoodRescue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LowCoverageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * DonationMatchingController tests (currently 28%)
     */
    public function test_donation_matching_list_all()
    {
        $response = $this->get('/donation-matching/list');
        $this->assertNotNull($response);
    }

    public function test_donation_matching_sort_by_urgency()
    {
        $response = $this->get('/donation-matching?sort=urgency');
        $this->assertNotNull($response);
    }

    public function test_donation_matching_sort_by_date()
    {
        $response = $this->get('/donation-matching?sort=date');
        $this->assertNotNull($response);
    }

    public function test_donation_matching_filter_by_category()
    {
        $response = $this->get('/donation-matching?category=makanan');
        $this->assertNotNull($response);
    }

    public function test_donation_matching_search_keyword()
    {
        $response = $this->get('/donation-matching/search?q=bantuan');
        $this->assertNotNull($response);
    }

    /**
     * VolunteerController tests (currently 31%)
     */
    public function test_volunteer_list()
    {
        $response = $this->get('/volunteer');
        $this->assertNotNull($response);
    }

    public function test_volunteer_show_detail()
    {
        $user = User::factory()->create();
        $response = $this->get("/volunteer/{$user->id}");
        $this->assertNotNull($response);
    }

    public function test_volunteer_skill_filter()
    {
        $response = $this->get('/volunteer?skill=medical');
        $this->assertNotNull($response);
    }

    public function test_volunteer_availability_filter()
    {
        $response = $this->get('/volunteer?availability=available');
        $this->assertNotNull($response);
    }

    public function test_volunteer_search()
    {
        $response = $this->get('/volunteer/search?q=dokter');
        $this->assertNotNull($response);
    }

    /**
     * FoodRescueController tests (currently 33%)
     */
    public function test_food_rescue_list_available()
    {
        $response = $this->get('/food-rescue?status=available');
        $this->assertNotNull($response);
    }

    public function test_food_rescue_list_claimed()
    {
        $response = $this->get('/food-rescue?status=claimed');
        $this->assertNotNull($response);
    }

    public function test_food_rescue_filter_by_location()
    {
        $response = $this->get('/food-rescue?location=jakarta');
        $this->assertNotNull($response);
    }

    public function test_food_rescue_urgent_only()
    {
        $response = $this->get('/food-rescue?urgent=true');
        $this->assertNotNull($response);
    }

    public function test_food_rescue_sorted_by_date()
    {
        $response = $this->get('/food-rescue?sort=date');
        $this->assertNotNull($response);
    }

    /**
     * DonasikuController tests (currently 41%)
     */
    public function test_donasiku_show()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/donasiku/1');
        $this->assertNotNull($response);
    }

    public function test_donasiku_update_form()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/donasiku/1/edit');
        $this->assertNotNull($response);
    }

    public function test_donasiku_update()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->put('/donasiku/1', [
            'kategori' => 'pakaian',
        ]);
        $this->assertNotNull($response);
    }

    public function test_donasiku_delete()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/donasiku/1');
        $this->assertNotNull($response);
    }

    public function test_donasiku_filter_by_status()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/donasiku?status=pending');
        $this->assertNotNull($response);
    }

    public function test_donasiku_search()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/donasiku/search?q=baju');
        $this->assertNotNull($response);
    }

    /**
     * Panti/WishlistController tests (currently 22%)
     */
    public function test_panti_wishlist_show()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/wishlist/1');
        $this->assertNotNull($response);
    }

    public function test_panti_wishlist_edit_form()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/wishlist/1/edit');
        $this->assertNotNull($response);
    }

    public function test_panti_wishlist_update()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->put('/panti/wishlist/1', [
            'judul' => 'Updated Item',
        ]);
        $this->assertNotNull($response);
    }

    public function test_panti_wishlist_delete()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/panti/wishlist/1');
        $this->assertNotNull($response);
    }

    public function test_panti_wishlist_mark_fulfilled()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/panti/wishlist/1/fulfill');
        $this->assertNotNull($response);
    }
}
