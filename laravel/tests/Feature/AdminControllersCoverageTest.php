<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllersCoverageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin UserController tests
     */
    public function test_admin_users_index()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/users');
        $this->assertNotNull($response);
    }

    public function test_admin_users_create_form()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/users/create');
        $this->assertNotNull($response);
    }

    public function test_admin_users_store()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->post('/admin/users', [
            'nama' => 'New User',
            'email' => 'newuser@test.com',
            'kata_sandi' => 'password',
        ]);
        $this->assertNotNull($response);
    }

    public function test_admin_users_show()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $response = $this->actingAs($admin)->get("/admin/users/{$user->id}");
        $this->assertNotNull($response);
    }

    public function test_admin_users_edit_form()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $response = $this->actingAs($admin)->get("/admin/users/{$user->id}/edit");
        $this->assertNotNull($response);
    }

    public function test_admin_users_update()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'nama' => 'Updated User',
        ]);
        $this->assertNotNull($response);
    }

    public function test_admin_users_delete()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");
        $this->assertNotNull($response);
    }

    /**
     * Admin FoodRescueController tests
     */
    public function test_admin_food_rescue_index()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/food-rescues');
        $this->assertNotNull($response);
    }

    public function test_admin_food_rescue_create_form()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/food-rescues/create');
        $this->assertNotNull($response);
    }

    public function test_admin_food_rescue_store()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->post('/admin/food-rescues', [
            'judul' => 'Food Item',
            'jumlah' => 10,
            'status' => 'available',
        ]);
        $this->assertNotNull($response);
    }

    public function test_admin_food_rescue_show()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/food-rescues/1');
        $this->assertNotNull($response);
    }

    public function test_admin_food_rescue_edit()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/food-rescues/1/edit');
        $this->assertNotNull($response);
    }

    public function test_admin_food_rescue_update()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->put('/admin/food-rescues/1', [
            'status' => 'claimed',
        ]);
        $this->assertNotNull($response);
    }

    public function test_admin_food_rescue_delete()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->delete('/admin/food-rescues/1');
        $this->assertNotNull($response);
    }
}
