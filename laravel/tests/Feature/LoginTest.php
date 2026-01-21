<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_loads_successfully()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    /** @test */
    public function login_with_invalid_credentials_fails()
    {
        $response = $this->post(route('login.submit'), [
            'email' => 'nonexistent@test.com',
            'password' => 'Password123',
        ]);

        $this->assertTrue($response->status() === 302 || $response->status() === 200);
        $this->assertGuest();
    }

    /** @test */
    public function login_with_valid_credentials_succeeds()
    {
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'kata_sandi' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'user@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function logout_clears_authentication()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }

    /** @test */
    public function validation_errors_shown_for_missing_credentials()
    {
        $response = $this->post(route('login.submit'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }
}
