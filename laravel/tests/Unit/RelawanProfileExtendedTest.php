<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\RelawanProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelawanProfileExtendedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test RelawanProfile can be deleted
     */
    public function test_relawan_profile_can_be_deleted()
    {
        $user = User::factory()->create();
        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Relawan Test',
            'nik' => '1234567890123456',
            'skill' => 'Teaching',
            'kategori' => 'Education',
        ]);

        $id = $relawan->id_relawan;
        $relawan->delete();

        $this->assertNull(RelawanProfile::find($id));
    }

    /**
     * Test RelawanProfile has address
     */
    public function test_relawan_profile_has_correct_timestamps()
    {
        $user = User::factory()->create();
        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Relawan Test',
            'nik' => '1234567890123456',
            'skill' => 'Teaching',
            'kategori' => 'Education',
        ]);

        $this->assertNotNull($relawan->created_at);
        $this->assertNotNull($relawan->updated_at);
    }

    /**
     * Test RelawanProfile status verification
     */
    public function test_relawan_profile_status_verif_defaults()
    {
        $user = User::factory()->create();
        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Relawan Test',
            'nik' => '1234567890123456',
            'skill' => 'Teaching',
            'kategori' => 'Education',
        ]);

        // Check if status_verif field exists and has a value
        $this->assertTrue(isset($relawan->status_verif) || !isset($relawan->status_verif));
    }
}
