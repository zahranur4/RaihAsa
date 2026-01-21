<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class PantiDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_user_cannot_access_panti_dashboard()
    {
        $response = $this->get(route('panti.dashboard'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_access_panti_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('panti.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_access_panti_wishlist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('panti.wishlist'));

        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    /** @test */
    public function authenticated_user_can_access_donasi_masuk()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('panti.donasi-masuk'));

        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    /** @test */
    public function authenticated_user_can_access_panti_food_rescue()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('panti.food-rescue'));

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_access_panti_laporan()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('panti.laporan'));

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_access_panti_profil()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('panti.profil'));

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_access_panti_pengaturan()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('panti.pengaturan'));

        $response->assertStatus(200);
    }
}
