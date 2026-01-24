<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PantiProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PantiProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test PantiProfile can be created
     */
    public function test_panti_profile_can_be_created()
    {
        $user = User::factory()->create();
        $pantiProfile = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Panti Asuhan Harapan',
            'alamat_lengkap' => 'Jl. Merdeka No. 123',
            'kota' => 'Jakarta',
        ]);

        $this->assertNotNull($pantiProfile->id_panti);
        $this->assertEquals('Panti Asuhan Harapan', $pantiProfile->nama_panti);
    }

    /**
     * Test PantiProfile has address
     */
    public function test_panti_profile_has_address()
    {
        $user = User::factory()->create();
        $pantiProfile = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat_lengkap' => 'Jl. Test No. 1',
            'kota' => 'Jakarta',
        ]);

        $this->assertEquals('Jl. Test No. 1', $pantiProfile->alamat_lengkap);
    }

    /**
     * Test PantiProfile has city
     */
    public function test_panti_profile_has_city()
    {
        $user = User::factory()->create();
        $pantiProfile = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat_lengkap' => 'Jl. Test',
            'kota' => 'Bandung',
        ]);

        $this->assertEquals('Bandung', $pantiProfile->kota);
    }

    /**
     * Test PantiProfile can be updated
     */
    public function test_panti_profile_can_be_updated()
    {
        $user = User::factory()->create();
        $pantiProfile = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Nama Lama',
            'alamat_lengkap' => 'Alamat Lama',
            'kota' => 'Jakarta',
        ]);

        $pantiProfile->update([
            'nama_panti' => 'Nama Baru',
            'kota' => 'Surabaya',
        ]);

        $this->assertEquals('Nama Baru', $pantiProfile->nama_panti);
        $this->assertEquals('Surabaya', $pantiProfile->kota);
    }

    /**
     * Test PantiProfile fillable attributes
     */
    public function test_panti_profile_fillable_attributes()
    {
        $pantiProfile = new PantiProfile();
        
        $this->assertContains('id_user', $pantiProfile->getFillable());
        $this->assertContains('nama_panti', $pantiProfile->getFillable());
        $this->assertContains('alamat_lengkap', $pantiProfile->getFillable());
        $this->assertContains('kota', $pantiProfile->getFillable());
    }
}
