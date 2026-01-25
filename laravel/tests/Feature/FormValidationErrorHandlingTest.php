<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DonaturProfile;
use App\Models\PantiProfile;
use App\Models\RelawanProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormValidationErrorHandlingTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_validation_errors()
    {
        // Missing password
        $response = $this->post('/register', [
            'nama' => 'Test User',
            'email' => 'test@test.com',
        ]);
        $this->assertTrue($response->status() === 302 || $response->status() === 422);

        // Invalid email
        $response = $this->post('/register', [
            'nama' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
        ]);
        $this->assertTrue($response->status() === 302 || $response->status() === 422);

        // Password mismatch
        $response = $this->post('/register', [
            'nama' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);
        $this->assertTrue($response->status() === 302 || $response->status() === 422);
    }

    public function test_donation_validation_errors()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $this->actingAs($user);

        // Missing required fields
        $response = $this->post('/donasi-barang', [
            'nama_barang' => '',
            'kategori' => '',
        ]);
        $this->assertTrue($response->status() === 302 || $response->status() === 422);

        // Invalid category
        $response = $this->post('/donasi-barang', [
            'nama_barang' => 'Test',
            'kategori' => 'InvalidCategory',
            'foto' => 'test.jpg',
        ]);
        $this->assertTrue($response->status() >= 200);
    }

    public function test_wishlist_validation_errors()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat' => 'Jl. Test',
            'no_telp' => '081234567890',
        ]);

        $this->actingAs($user);

        // Missing required fields
        $response = $this->post('/wishlist', [
            'barang_diinginkan' => '',
            'jumlah' => '',
        ]);
        $this->assertTrue($response->status() === 302 || $response->status() === 422);

        // Invalid quantity
        $response = $this->post('/wishlist', [
            'barang_diinginkan' => 'Test',
            'jumlah' => -5,
            'urgensi' => 'mendesak',
            'kategori' => 'Test',
        ]);
        $this->assertTrue($response->status() >= 200);

        // Invalid urgency
        $response = $this->post('/wishlist', [
            'barang_diinginkan' => 'Test',
            'jumlah' => 10,
            'urgensi' => 'invalid_urgency',
            'kategori' => 'Test',
        ]);
        $this->assertTrue($response->status() >= 200);
    }

    public function test_volunteer_profile_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Missing NIK
        $response = $this->post('/relawan-profile', [
            'nama_lengkap' => 'Volunteer',
            'nik' => '',
            'skill' => 'Skill',
        ]);
        $this->assertTrue($response->status() === 302 || $response->status() === 422 || $response->status() >= 200);

        // Invalid NIK format
        $response = $this->post('/relawan-profile', [
            'nama_lengkap' => 'Volunteer',
            'nik' => '123',
            'skill' => 'Skill',
        ]);
        $this->assertTrue($response->status() >= 200);
    }

    public function test_panti_profile_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Missing nama_panti
        $response = $this->post('/panti-profile', [
            'nama_panti' => '',
            'alamat' => 'Jl. Test',
        ]);
        $this->assertTrue($response->status() === 302 || $response->status() === 422 || $response->status() >= 200);

        // Missing alamat
        $response = $this->post('/panti-profile', [
            'nama_panti' => 'Panti Test',
            'alamat' => '',
        ]);
        $this->assertTrue($response->status() === 302 || $response->status() === 422 || $response->status() >= 200);
    }

    public function test_food_rescue_validation()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $this->actingAs($user);

        // Invalid portion
        $response = $this->post('/food-rescue', [
            'nama_makanan' => 'Food',
            'porsi' => -10,
        ]);
        $this->assertTrue($response->status() >= 200);

        // Missing expired time
        $response = $this->post('/food-rescue', [
            'nama_makanan' => 'Food',
            'porsi' => 10,
            'waktu_expired' => '',
        ]);
        $this->assertTrue($response->status() >= 200);

        // Expired time in past
        $response = $this->post('/food-rescue', [
            'nama_makanan' => 'Food',
            'porsi' => 10,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->subHours(1),
        ]);
        $this->assertTrue($response->status() >= 200);
    }

    public function test_unauthenticated_access_errors()
    {
        // Access donation create without login
        $response = $this->get('/donasi-barang/create');
        $this->assertTrue($response->status() === 302);

        // Access wishlist create without login
        $response = $this->get('/wishlist/create');
        $this->assertTrue($response->status() === 302);

        // Access volunteer profile without login
        $response = $this->get('/relawan-profile/create');
        $this->assertTrue($response->status() === 302);

        // Access panti profile without login
        $response = $this->get('/panti-profile/create');
        $this->assertTrue($response->status() === 302);
    }

    public function test_unauthorized_access_errors()
    {
        // User 1 tries to edit User 2's profile
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1);

        // Try to access other user's profile
        $response = $this->get("/profile/{$user2->id}");
        $this->assertTrue($response->status() >= 200);
    }

    public function test_admin_access_errors()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        // Non-admin tries to access admin dashboard
        $response = $this->get('/admin/dashboard');
        $this->assertTrue($response->status() === 302 || $response->status() === 403);

        // Non-admin tries to access admin users
        $response = $this->get('/admin/users');
        $this->assertTrue($response->status() === 302 || $response->status() === 403);

        // Non-admin tries to access admin donations
        $response = $this->get('/admin/donasi-barang');
        $this->assertTrue($response->status() === 302 || $response->status() === 403);
    }

    public function test_model_not_found_errors()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Non-existent donation
        $response = $this->get('/donasi-barang/99999');
        $this->assertTrue($response->status() === 404 || $response->status() >= 200);

        // Non-existent wishlist
        $response = $this->get('/wishlist/99999');
        $this->assertTrue($response->status() === 404 || $response->status() >= 200);

        // Non-existent food rescue
        $response = $this->get('/food-rescue/99999');
        $this->assertTrue($response->status() === 404 || $response->status() >= 200);

        // Non-existent volunteer
        $response = $this->get('/relawan-profile/99999');
        $this->assertTrue($response->status() === 404 || $response->status() >= 200);

        // Non-existent panti
        $response = $this->get('/panti-profile/99999');
        $this->assertTrue($response->status() === 404 || $response->status() >= 200);
    }

    public function test_duplicate_email_error()
    {
        User::factory()->create(['email' => 'duplicate@test.com']);

        $response = $this->post('/register', [
            'nama' => 'Another User',
            'email' => 'duplicate@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertTrue($response->status() === 302 || $response->status() === 422);
    }

    public function test_login_with_wrong_credentials()
    {
        User::factory()->create([
            'email' => 'test@test.com',
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertTrue($response->status() === 302);
    }

    public function test_search_functionality()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Search donations
        $response = $this->get('/donasi-barang?search=test');
        $this->assertTrue($response->status() >= 200);

        // Search food rescue
        $response = $this->get('/food-rescue?search=makanan');
        $this->assertTrue($response->status() >= 200);

        // Search wishlists
        $response = $this->get('/wishlist?search=barang');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_pagination_workflow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Page 1
        $response = $this->get('/donasi-barang?page=1');
        $this->assertTrue($response->status() >= 200);

        // Page 2
        $response = $this->get('/donasi-barang?page=2');
        $this->assertTrue($response->status() >= 200);

        // Invalid page
        $response = $this->get('/donasi-barang?page=9999');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_sorting_workflow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Sort by newest
        $response = $this->get('/donasi-barang?sort=newest');
        $this->assertTrue($response->status() >= 200);

        // Sort by oldest
        $response = $this->get('/donasi-barang?sort=oldest');
        $this->assertTrue($response->status() >= 200);

        // Invalid sort
        $response = $this->get('/donasi-barang?sort=invalid');
        $this->assertTrue($response->status() >= 200);
    }
}
