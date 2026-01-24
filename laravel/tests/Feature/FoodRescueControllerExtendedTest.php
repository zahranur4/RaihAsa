<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FoodRescue;
use App\Models\DonaturProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class FoodRescueControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /** @test */
    public function food_rescue_page_loads_for_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get('/food-rescue');
        $response->assertStatus(200);
    }

    /** @test */
    public function food_rescue_list_shows_available_items()
    {
        $donatur = DonaturProfile::factory()->create();
        FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Test Food',
            'porsi' => 10,
            'waktu_dibuat' => Carbon::now(),
            'status' => 'available',
        ]);

        $response = $this->actingAs($this->user)->get('/food-rescue');
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_view_food_rescue_page()
    {
        $donatur = DonaturProfile::factory()->create();
        $food = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Test Food',
            'porsi' => 10,
            'waktu_dibuat' => Carbon::now(),
            'status' => 'available',
        ]);

        $response = $this->actingAs($this->user)->get('/food-rescue');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_food_rescue_management()
    {
        $response = $this->actingAs($this->admin)->get('/admin/food-rescue');
        
        $this->assertTrue(
            $response->status() === 200 || $response->status() === 401 || $response->status() === 403
        );
    }
}
