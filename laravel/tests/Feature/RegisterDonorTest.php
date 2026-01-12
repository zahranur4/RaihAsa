<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterDonorTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_errors_for_missing_required_fields()
    {
        $response = $this->post(route('register.donor'), []);

        $response->assertSessionHasErrors(['nama', 'email', 'kata_sandi']);
    }

    public function test_successful_donor_registration_creates_user()
    {
        $payload = [
            'nama' => 'Donor Contoh',
            'email' => 'donor@example.test',
            'kata_sandi' => 'Password1',
            'nomor_telepon' => '081234567890',
            'alamat' => 'Jalan Donor No 1',
        ];

        $response = $this->post(route('register.donor'), $payload);

        // Should redirect to home
        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', ['email' => 'donor@example.test', 'nama' => 'Donor Contoh']);

        // ensure user is logged in
        $user = User::where('email', 'donor@example.test')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
    }
}
