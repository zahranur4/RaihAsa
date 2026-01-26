<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test User model has correct fillable attributes
     */
    public function test_user_has_correct_fillable_attributes()
    {
        $user = User::factory()->create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertEquals('John Doe', $user->nama);
        $this->assertEquals('john@example.com', $user->email);
    }

    /**
     * Test User get name attribute returns nama
     */
    public function test_user_get_name_attribute_returns_nama()
    {
        $user = User::factory()->create(['nama' => 'Test User']);

        $this->assertEquals('Test User', $user->name);
    }

    /**
     * Test User can have many relawan profiles
     */
    public function test_user_has_many_relawan_profiles()
    {
        $user = User::factory()->create();

        $this->assertTrue(method_exists($user, 'relawan_profiles'));
    }

    /**
     * Test User is admin cast boolean
     */
    public function test_user_is_admin_casts_to_boolean()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->assertTrue($user->is_admin);
        $this->assertIsBool($user->is_admin);
    }

    /**
     * Test User email attribute
     */
    public function test_user_has_email()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $this->assertEquals('test@example.com', $user->email);
    }

    /**
     * Test User address attribute
     */
    public function test_user_has_address()
    {
        $user = User::factory()->create(['alamat' => 'Jl. Test No. 123']);

        $this->assertEquals('Jl. Test No. 123', $user->alamat);
    }

    /**
     * Test User phone number attribute
     */
    public function test_user_has_phone_number()
    {
        $user = User::factory()->create(['nomor_telepon' => '081234567890']);

        $this->assertEquals('081234567890', $user->nomor_telepon);
    }

    /**
     * Test User hidden attributes
     */
    public function test_user_hides_password_on_serialization()
    {
        $user = User::factory()->create();

        $this->assertArrayNotHasKey('kata_sandi', $user->toArray());
    }

    /**
     * Test User getAuthPassword method returns kata_sandi
     */
    public function test_user_get_auth_password_returns_kata_sandi()
    {
        $user = User::factory()->create(['kata_sandi' => 'hashedpassword']);

        $this->assertEquals('hashedpassword', $user->getAuthPassword());
    }

    /**
     * Test User relawan_profiles relationship
     */
    public function test_user_relawan_profiles_relationship()
    {
        $user = User::factory()->create();

        // Test that the relationship method exists and returns correct type
        $relationship = $user->relawan_profiles();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relationship);

        // Test the relationship query
        $this->assertEquals('id_user', $relationship->getForeignKeyName());
        $this->assertEquals('id', $relationship->getLocalKeyName());
    }

    /**
     * Test User getNameAttribute accessor is called
     */
    public function test_user_get_name_attribute_accessor()
    {
        $user = User::factory()->create(['nama' => 'Accessor Test']);

        // Access the name attribute to trigger the accessor
        $name = $user->getNameAttribute();

        $this->assertEquals('Accessor Test', $name);
    }
}
