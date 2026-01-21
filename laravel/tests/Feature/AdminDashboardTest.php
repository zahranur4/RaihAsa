<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_admin_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $this->assertTrue(in_array($response->status(), [302, 403]));
    }

    /** @test */
    public function admin_user_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_user_cannot_access_manajemen_relawan()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->get(route('admin.volunteers.index'));

        $this->assertTrue(in_array($response->status(), [302, 403]));
    }

    /** @test */
    public function admin_user_can_access_manajemen_relawan()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.volunteers.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_manajemen_donasi()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.donations.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_manajemen_penerima()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.recipients.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_food_rescue()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.food-rescue.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_manajemen_pengguna()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_settings()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.settings.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_reports()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.index'));

        $response->assertStatus(200);
    }
}
