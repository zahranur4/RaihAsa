<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\RelawanProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelawanProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test RelawanProfile can be created
     */
    public function test_relawan_profile_can_be_created()
    {
        $user = User::factory()->create();
        $relawanProfile = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Relawan Test',
            'nik' => '1234567890123456',
            'skill' => 'Kesehatan',
            'kategori' => 'Medis',
        ]);

        $this->assertNotNull($relawanProfile->id_relawan);
        $this->assertEquals('Relawan Test', $relawanProfile->nama_lengkap);
    }

    /**
     * Test RelawanProfile skill
     */
    public function test_relawan_profile_skill()
    {
        $user = User::factory()->create();
        $relawanProfile = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test',
            'nik' => '1234567890123456',
            'skill' => 'Pendidikan',
        ]);

        $this->assertEquals('Pendidikan', $relawanProfile->skill);
    }

    /**
     * Test RelawanProfile kategori
     */
    public function test_relawan_profile_kategori()
    {
        $user = User::factory()->create();
        $relawanProfile = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test',
            'nik' => '1234567890123456',
            'kategori' => 'Medis',
        ]);

        $this->assertEquals('Medis', $relawanProfile->kategori);
    }

    /**
     * Test RelawanProfile can be updated
     */
    public function test_relawan_profile_can_be_updated()
    {
        $user = User::factory()->create();
        $relawanProfile = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Nama Lama',
            'nik' => '1234567890123456',
            'skill' => 'Kesehatan',
        ]);

        $relawanProfile->update([
            'nama_lengkap' => 'Nama Baru',
            'skill' => 'Pendidikan',
        ]);

        $this->assertEquals('Nama Baru', $relawanProfile->nama_lengkap);
        $this->assertEquals('Pendidikan', $relawanProfile->skill);
    }

    /**
     * Test RelawanProfile fillable attributes
     */
    public function test_relawan_profile_fillable_attributes()
    {
        $relawanProfile = new RelawanProfile();
        
        $this->assertContains('id_user', $relawanProfile->getFillable());
        $this->assertContains('nama_lengkap', $relawanProfile->getFillable());
        $this->assertContains('nik', $relawanProfile->getFillable());
        $this->assertContains('skill', $relawanProfile->getFillable());
    }

    /**
     * Test RelawanProfile nik
     */
    public function test_relawan_profile_has_nik()
    {
        $user = User::factory()->create();
        $relawanProfile = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test',
            'nik' => '9876543210987654',
            'skill' => 'Test',
        ]);

        $this->assertEquals('9876543210987654', $relawanProfile->nik);
    }
}
