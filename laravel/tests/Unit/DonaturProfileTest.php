<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DonaturProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DonaturProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test DonaturProfile can be created
     */
    public function test_donatur_profile_can_be_created()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'John Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor No. 123',
        ]);

        $this->assertNotNull($donatur->id_donatur);
        $this->assertEquals('John Donor', $donatur->nama_lengkap);
    }

    /**
     * Test DonaturProfile has correct fillable attributes
     */
    public function test_donatur_profile_fillable_attributes()
    {
        $attributes = [
            'id_user',
            'nama_lengkap',
            'no_telp',
            'alamat_jemput',
        ];

        $donatur = new DonaturProfile();
        
        foreach ($attributes as $attr) {
            $this->assertContains($attr, $donatur->getFillable());
        }
    }

    /**
     * Test DonaturProfile can be updated
     */
    public function test_donatur_profile_can_be_updated()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Old Name',
            'no_telp' => '081234567890',
        ]);

        $donatur->update(['nama_lengkap' => 'New Name']);

        $this->assertEquals('New Name', $donatur->nama_lengkap);
    }

    /**
     * Test DonaturProfile has phone number
     */
    public function test_donatur_profile_has_phone_number()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'John Donor',
            'no_telp' => '081234567890',
        ]);

        $this->assertEquals('081234567890', $donatur->no_telp);
    }

    /**
     * Test DonaturProfile belongs to user
     */
    public function test_donatur_profile_belongs_to_user()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'John Donor',
            'no_telp' => '081234567890',
        ]);

        $this->assertNotNull($donatur->user);
        $this->assertEquals($user->id, $donatur->user->id);
    }

    /**
     * Test DonaturProfile can be deleted
     */
    public function test_donatur_profile_can_be_deleted()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'John Donor',
            'no_telp' => '081234567890',
        ]);

        $id = $donatur->id_donatur;
        $donatur->delete();

        $this->assertNull(DonaturProfile::find($id));
    }
}
