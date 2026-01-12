<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterRecipientTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_errors_for_missing_required_fields()
    {
        $response = $this->post(route('register.recipient'), []);

        $response->assertSessionHasErrors(['nama', 'email', 'kata_sandi', 'nik']);
    }

    public function test_successful_registration_and_file_upload()
    {
        Storage::fake('public');

        $payload = [
            'nama' => 'Panti Contoh',
            'email' => 'panti@example.test',
            'kata_sandi' => 'Password1',
            'nomor_telepon' => '081234567890',
            'alamat' => 'Jalan Contoh No 1',
            'kode_pos' => '12345',
            'jenis' => 'orphanage',
            'kapasitas' => 25,
            'nik' => '1234567890123456',
            'doc_akte' => UploadedFile::fake()->create('akte.pdf', 100, 'application/pdf'),
            'doc_sk' => UploadedFile::fake()->create('sk.pdf', 100, 'application/pdf'),
            'doc_npwp' => UploadedFile::fake()->create('npwp.pdf', 100, 'application/pdf'),
            'doc_other' => UploadedFile::fake()->create('other.pdf', 100, 'application/pdf'),
        ];

        $response = $this->post(route('register.recipient'), $payload);

        $response->assertRedirect(route('panti.dashboard'));

        $this->assertDatabaseHas('users', ['email' => 'panti@example.test']);

        $user = User::where('email', 'panti@example.test')->first();
        $this->assertNotNull($user);

        $this->assertDatabaseHas('panti_asuhan', ['user_id' => $user->id, 'nama' => 'Panti Contoh', 'nik' => '1234567890123456']);

        $panti = DB::table('panti_asuhan')->where('user_id', $user->id)->first();
        $this->assertNotNull($panti);
        $this->assertNotEmpty($panti->doc_akte);

        // Files should be stored on the 'public' disk
        Storage::disk('public')->assertExists($panti->doc_akte);
        Storage::disk('public')->assertExists($panti->doc_sk);
        Storage::disk('public')->assertExists($panti->doc_npwp);
        Storage::disk('public')->assertExists($panti->doc_other);
    }
}
