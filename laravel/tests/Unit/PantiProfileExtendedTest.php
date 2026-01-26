<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PantiProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PantiProfileExtendedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test PantiProfile can be deleted
     */
    public function test_panti_profile_can_be_deleted()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat_lengkap' => 'Jl. Test No. 123',
            'kota' => 'Jakarta',
        ]);

        $id = $panti->id_panti;
        $panti->delete();

        $this->assertNull(PantiProfile::find($id));
    }

    /**
     * Test PantiProfile has latitude longitude
     */
    public function test_panti_profile_has_coordinates()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat_lengkap' => 'Jl. Test No. 123',
            'kota' => 'Jakarta',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $this->assertEquals(-6.2088, $panti->latitude);
        $this->assertEquals(106.8456, $panti->longitude);
    }

    /**
     * Test PantiProfile has SK number
     */
    public function test_panti_profile_has_sk_number()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat_lengkap' => 'Jl. Test No. 123',
            'kota' => 'Jakarta',
            'no_sk' => 'SK/001/2026',
        ]);

        $this->assertEquals('SK/001/2026', $panti->no_sk);
    }

    /**
     * Test PantiProfile belongsTo user
     */
    public function test_panti_profile_belongs_to_user()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat_lengkap' => 'Jl. Test No. 123',
            'kota' => 'Jakarta',
        ]);

        $this->assertNotNull($panti->user);
        $this->assertEquals($user->id, $panti->user->id);
    }
}
