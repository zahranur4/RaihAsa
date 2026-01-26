<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\RelawanProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelawanProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_access_relawan_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.relawan.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_delete_relawan_profile()
    {
        // Asumsi RelawanProfile dibuat manual atau via factory jika ada
        $user = User::factory()->create();
        $profile = new RelawanProfile(['user_id' => $user->id]);
        $profile->save();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.relawan.destroy', $profile));

        $response->assertRedirect(route('admin.relawan.index'));
        $this->assertDatabaseMissing('relawan_profiles', ['id' => $profile->id]);
    }
}
