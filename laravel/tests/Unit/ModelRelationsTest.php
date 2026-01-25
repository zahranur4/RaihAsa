<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\PantiProfile;
use App\Models\RelawanProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Real tests untuk model relations & methods
     */
    public function test_user_model_exists()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user->id);
        $this->assertIsInt($user->id);
    }

    public function test_user_fillable_attributes()
    {
        $user = User::factory()->make();
        $this->assertNotNull($user->nama);
        $this->assertNotNull($user->email);
    }

    public function test_user_hidden_attributes()
    {
        $user = User::factory()->create();
        $array = $user->toArray();
        $this->assertArrayNotHasKey('kata_sandi', $array);
    }

    public function test_panti_profile_creation()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::factory()->create(['user_id' => $user->id]);
        $this->assertNotNull($panti->id);
    }

    public function test_panti_belongs_to_user()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::factory()->create(['user_id' => $user->id]);
        $this->assertEquals($user->id, $panti->user->id);
    }

    public function test_relawan_profile_creation()
    {
        $user = User::factory()->create();
        $relawan = RelawanProfile::factory()->create(['user_id' => $user->id]);
        $this->assertNotNull($relawan->id);
    }

    public function test_user_casting_is_admin()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $this->assertTrue($user->is_admin);
    }

    public function test_user_casting_is_admin_false()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->assertFalse($user->is_admin);
    }

    public function test_user_timestamps()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }

    public function test_model_attribute_casting()
    {
        $user = User::factory()->create();
        $this->assertIsString($user->email);
    }

    public function test_panti_model_fillable()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'user_id' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat_lengkap' => 'Jl Test',
            'kota' => 'Jakarta',
        ]);
        $this->assertEquals('Test Panti', $panti->nama_panti);
    }

    public function test_relawan_model_fillable()
    {
        $user = User::factory()->create();
        $relawan = RelawanProfile::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'kategori_skill' => 'medical',
        ]);
        $this->assertEquals('1234567890123456', $relawan->nik);
    }
}
