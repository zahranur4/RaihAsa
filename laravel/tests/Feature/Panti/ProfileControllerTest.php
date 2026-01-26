<?php

namespace Tests\Feature\Panti;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_panti_can_view_profile_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('panti.profil'));

        $response->assertStatus(200);
        $response->assertViewIs('panti.profil.index');
    }

    public function test_panti_can_update_profile()
    {
        $user = User::factory()->create();
        \Illuminate\Support\Facades\DB::table('panti_asuhan')->insert([
            'user_id' => $user->id,
            'nama' => 'Panti Asuhan Lama',
            'jenis' => 'orphanage',
            'nik' => '1234567890123456',
        ]);

        $this->actingAs($user);

        $updateData = [
            'nama' => 'Panti Asuhan Baru',
            'jenis' => 'foundation',
            'nomor_telepon' => '08123456789',
            'alamat' => 'Alamat Baru',
            'nik' => '1234567890123456',
        ];

        $response = $this->post(route('panti.profil.update'), $updateData);

        $response->assertRedirect(route('panti.profil'));
        $this->assertDatabaseHas('panti_asuhan', [
            'user_id' => $user->id,
            'nama' => 'Panti Asuhan Baru',
            'jenis' => 'foundation',
            'nomor_telepon' => '08123456789',
            'alamat' => 'Alamat Baru',
        ]);
    }

    public function test_new_panti_can_create_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $createData = [
            'nama' => 'Panti Asuhan Baru',
            'jenis' => 'foundation',
            'nomor_telepon' => '08123456789',
            'alamat' => 'Alamat Baru',
            'nik' => '1234567890123456',
        ];

        $response = $this->post(route('panti.profil.update'), $createData);

        $response->assertRedirect(route('panti.profil'));
        $this->assertDatabaseHas('panti_asuhan', [
            'user_id' => $user->id,
            'nama' => 'Panti Asuhan Baru',
            'jenis' => 'foundation',
            'nomor_telepon' => '08123456789',
            'alamat' => 'Alamat Baru',
        ]);
    }

    public function test_panti_can_upload_document()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create();
        \Illuminate\Support\Facades\DB::table('panti_asuhan')->insert([
            'user_id' => $user->id,
            'nama' => 'Panti Asuhan Lama',
            'jenis' => 'orphanage',
            'nik' => '1234567890123456',
        ]);

        $this->actingAs($user);

        $file = \Illuminate\Http\UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $updateData = [
            'nama' => 'Panti Asuhan Baru',
            'jenis' => 'foundation',
            'nik' => '1234567890123456',
            'doc_akte' => $file,
        ];

        $response = $this->post(route('panti.profil.update'), $updateData);

        $response->assertRedirect(route('panti.profil'));

        $panti = \Illuminate\Support\Facades\DB::table('panti_asuhan')->where('user_id', $user->id)->first();
        $this->assertNotNull($panti->doc_akte);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($panti->doc_akte);
    }
}
