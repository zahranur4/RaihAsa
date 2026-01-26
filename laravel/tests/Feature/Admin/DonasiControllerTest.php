<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\DonasiBarang;
use App\Models\DonaturProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DonasiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['is_admin' => true]);
    }

    /** @test */
    public function admin_can_view_donations_index()
    {
        DonasiBarang::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('admin.donations.index'));

        $response->assertStatus(200);
        $response->assertViewHas('donations');
    }

    /** @test */
    public function admin_can_create_donation_form()
    {
        $response = $this->actingAs($this->user)->get(route('admin.donations.create'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_store_donation()
    {
        $donatur = DonaturProfile::factory()->create();
        $data = [
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Buku Pelajaran',
            'kategori' => 'Pendidikan',
            'status' => 'pending',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.donations.store'), $data);

        $response->assertRedirect(route('admin.donations.index'));
        $this->assertDatabaseHas('donasi_barang', $data);
    }

    /** @test */
    public function admin_can_edit_donation()
    {
        $donatur = DonaturProfile::factory()->create();
        $donation = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Item Lama',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.donations.edit', $donation->id_donasi));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_donation()
    {
        $donatur = DonaturProfile::factory()->create();
        $donation = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Item Lama',
            'status' => 'pending',
        ]);

        $data = ['id_donatur' => $donatur->id_donatur, 'nama_barang' => 'Item Baru', 'status' => 'delivered'];

        $response = $this->actingAs($this->user)
            ->put(route('admin.donations.update', $donation->id_donasi), $data);

        $response->assertRedirect(route('admin.donations.index'));
        $this->assertDatabaseHas('donasi_barang', ['id_donasi' => $donation->id_donasi, 'nama_barang' => 'Item Baru']);
    }

    /** @test */
    public function admin_can_delete_donation()
    {
        $donatur = DonaturProfile::factory()->create();
        $donation = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Item untuk dihapus',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('admin.donations.destroy', $donation->id_donasi));

        $response->assertRedirect(route('admin.donations.index'));
        $this->assertModelMissing($donation);
    }

    /** @test */
    public function index_returns_donation_statistics()
    {
        DonasiBarang::factory()->create(['status' => 'delivered']);
        DonasiBarang::factory()->create(['status' => 'pending']);
        DonasiBarang::factory()->create(['status' => 'accepted']);

        $response = $this->actingAs($this->user)->get(route('admin.donations.index'));

        $response->assertStatus(200);
        $response->assertViewHas('stats');
    }
}
