<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\FoodRescue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodRescueControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Membuat user dengan role admin
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function admin_can_access_food_rescue_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.food-rescue.index'));

        $response->assertStatus(200);
        // Memastikan view yang dirender benar (sesuaikan jika nama view berbeda)
        $response->assertViewIs('admin.food-rescue.index');
    }

    /** @test */
    public function admin_can_delete_food_rescue()
    {
        $foodRescue = FoodRescue::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.food-rescue.destroy', $foodRescue));

        $response->assertRedirect(route('admin.food-rescue.index'));
        $this->assertDatabaseMissing('food_rescues', ['id' => $foodRescue->id]);
    }
}
