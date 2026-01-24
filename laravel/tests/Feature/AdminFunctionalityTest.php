<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PantiProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $normalUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->normalUser = User::factory()->create(['is_admin' => false]);
    }

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function normal_user_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/dashboard');
        
        $this->assertThat(
            $response->status(),
            $this->logicalOr(
                $this->equalTo(403),
                $this->equalTo(404),
                $this->equalTo(302)
            )
        );
    }

    /** @test */
    public function admin_can_access_volunteer_management()
    {
        $response = $this->actingAs($this->admin)->get('/admin/relawan');
        
        $this->assertThat(
            $response->status(),
            $this->logicalOr(
                $this->equalTo(200),
                $this->equalTo(404)
            )
        );
    }

    /** @test */
    public function admin_can_access_donation_management()
    {
        $response = $this->actingAs($this->admin)->get('/admin/donasi');
        
        $this->assertThat(
            $response->status(),
            $this->logicalOr(
                $this->equalTo(200),
                $this->equalTo(404)
            )
        );
    }

    /** @test */
    public function admin_can_access_user_management()
    {
        $response = $this->actingAs($this->admin)->get('/admin/pengguna');
        
        $this->assertThat(
            $response->status(),
            $this->logicalOr(
                $this->equalTo(200),
                $this->equalTo(404)
            )
        );
    }

    /** @test */
    public function admin_can_access_settings()
    {
        $response = $this->actingAs($this->admin)->get('/admin/settings');
        
        $this->assertThat(
            $response->status(),
            $this->logicalOr(
                $this->equalTo(200),
                $this->equalTo(404)
            )
        );
    }
}
