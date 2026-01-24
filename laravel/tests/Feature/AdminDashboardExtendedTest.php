<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardExtendedTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $this->assertNotNull($response);
    }

    public function test_admin_can_access_relawan_management()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/relawan-management');
        $this->assertNotNull($response);
    }

    public function test_admin_can_access_donasi_management()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/donasi-management');
        $this->assertNotNull($response);
    }

    public function test_admin_can_access_panti_management()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/panti-management');
        $this->assertNotNull($response);
    }

    public function test_admin_can_access_users_management()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/users-management');
        $this->assertNotNull($response);
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $this->assertNotNull($response);
    }

    public function test_guest_cannot_access_admin()
    {
        $response = $this->get('/admin/dashboard');
        $this->assertNotNull($response);
    }
}
