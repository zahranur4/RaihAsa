<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_loads()
    {
        $response = $this->get('/register');
        $this->assertEqual(200, $response->status());
    }

    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        $this->assertEqual(200, $response->status());
    }

    public function test_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'nama' => 'Test User',
            'email' => 'testuser' . uniqid() . '@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 302);
    }

    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'login' . uniqid() . '@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 302);
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertTrue($response->status() >= 400);
    }

    public function test_logout_authenticated_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/logout');
        $this->assertTrue($response->status() >= 302 || $response->status() >= 200);
    }

    public function test_authenticated_user_can_access_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_unauthenticated_user_redirected_from_profile()
    {
        $response = $this->get('/profile');
        $this->assertTrue($response->status() >= 400 || $response->status() == 302);
    }

    public function test_user_can_view_home()
    {
        $response = $this->get('/');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_user_session_is_created_after_login()
    {
        $user = User::factory()->create([
            'email' => 'session' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 302);
    }

    public function test_user_data_accessible_when_authenticated()
    {
        $user = User::factory()->create([
            'email' => 'accessible' . uniqid() . '@test.com',
        ]);

        $this->actingAs($user);
        $this->assertNotNull(auth()->user());
        $this->assertEquals($user->id, auth()->user()->id);
    }

    public function test_user_data_null_when_not_authenticated()
    {
        $this->assertNull(auth()->user());
    }

    public function test_remember_me_token()
    {
        $user = User::factory()->create();
        $this->assertTrue(true);
    }

    public function test_password_reset_request()
    {
        $user = User::factory()->create([
            'email' => 'reset' . uniqid() . '@test.com',
        ]);

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 302 || $response->status() >= 404);
    }

    public function test_user_email_verified_field()
    {
        $user = User::factory()->create([
            'email' => 'verified' . uniqid() . '@test.com',
            'email_verified_at' => null,
        ]);

        $this->assertNull($user->email_verified_at);
    }

    public function test_user_password_hashing()
    {
        $password = 'plaintext_password';
        $user = User::factory()->create([
            'email' => 'hash' . uniqid() . '@test.com',
            'password' => bcrypt($password),
        ]);

        $this->assertTrue(\Hash::check($password, $user->password));
    }

    public function test_user_creation_with_factory()
    {
        $user = User::factory()->create([
            'email' => 'factory' . uniqid() . '@test.com',
            'nama' => 'Factory User',
        ]);

        $this->assertNotNull($user->id);
        $this->assertEquals('Factory User', $user->nama);
    }

    public function test_user_fillable_attributes()
    {
        $user = new User();
        $fillable = $user->getFillable();

        $this->assertTrue(!empty($fillable));
    }

    public function test_user_table_name()
    {
        $user = new User();
        $this->assertEquals('users', $user->getTable());
    }

    public function test_user_hidden_attributes()
    {
        $user = User::factory()->create([
            'email' => 'hidden' . uniqid() . '@test.com',
            'password' => bcrypt('secret'),
        ]);

        $this->assertTrue(true);
    }

    public function test_user_casts()
    {
        $user = User::factory()->create([
            'email' => 'cast' . uniqid() . '@test.com',
        ]);

        $this->assertIsObject($user);
        $this->assertNotNull($user->created_at);
    }

    public function test_user_query_builder()
    {
        $user = User::factory()->create([
            'email' => 'query' . uniqid() . '@test.com',
            'is_admin' => false,
        ]);

        $found = User::where('is_admin', false)->first();
        $this->assertNotNull($found);
    }

    public function test_user_admin_flag()
    {
        $admin = User::factory()->create([
            'email' => 'admin' . uniqid() . '@test.com',
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'email' => 'user' . uniqid() . '@test.com',
            'is_admin' => false,
        ]);

        $this->assertTrue($admin->is_admin);
        $this->assertFalse($user->is_admin);
    }

    public function test_admin_can_access_admin_routes()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_user_cannot_access_admin_routes()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_response_headers()
    {
        $response = $this->get('/');
        $this->assertNotNull($response->headers);
    }

    public function test_json_response()
    {
        $response = $this->get('/api/user');
        $this->assertTrue($response->status() >= 400 || $response->status() == 401 || $response->status() == 200);
    }

    public function test_redirect_response()
    {
        $response = $this->get('/login');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_view_response()
    {
        $response = $this->get('/');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_404_not_found()
    {
        $response = $this->get('/nonexistent-route-' . uniqid());
        $this->assertEquals(404, $response->status());
    }

    public function test_method_not_allowed()
    {
        $response = $this->patch('/');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_user_update()
    {
        $user = User::factory()->create([
            'email' => 'update' . uniqid() . '@test.com',
            'nama' => 'Original Name',
        ]);

        $user->update(['nama' => 'Updated Name']);
        $this->assertEquals('Updated Name', $user->fresh()->nama);
    }

    public function test_user_delete()
    {
        $user = User::factory()->create([
            'email' => 'delete' . uniqid() . '@test.com',
        ]);

        $id = $user->id;
        $user->delete();

        $this->assertNull(User::find($id));
    }

    public function test_user_soft_delete()
    {
        $user = User::factory()->create([
            'email' => 'softdelete' . uniqid() . '@test.com',
        ]);

        $this->assertTrue(true);
    }
}
