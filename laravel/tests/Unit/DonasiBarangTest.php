<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DonasiBarang;
use App\Models\DonaturProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DonasiBarangTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test DonasiBarang model can be created
     */
    public function test_donasi_barang_can_be_created()
    {
        $donatur = DonaturProfile::factory()->create();
        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Buku Pelajaran',
            'kategori' => 'Pendidikan',
            'status' => 'pending',
        ]);

        $this->assertNotNull($donasi->id_donasi);
        $this->assertEquals('Buku Pelajaran', $donasi->nama_barang);
    }

    /**
     * Test DonasiBarang can be updated
     */
    public function test_donasi_barang_can_be_updated()
    {
        $donatur = DonaturProfile::factory()->create();
        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Buku Lama',
            'status' => 'pending',
        ]);

        $donasi->update(['status' => 'delivered']);

        $this->assertEquals('delivered', $donasi->status);
    }

    /**
     * Test DonasiBarang has correct fillable attributes
     */
    public function test_donasi_barang_fillable_attributes()
    {
        $attributes = [
            'id_donatur',
            'nama_barang',
            'kategori',
            'foto',
            'status',
            'id_panti',
        ];

        $donasi = new DonasiBarang();
        
        foreach ($attributes as $attr) {
            $this->assertContains($attr, $donasi->getFillable());
        }
    }

    /**
     * Test DonasiBarang status values
     */
    public function test_donasi_barang_status_values()
    {
        $donatur = DonaturProfile::factory()->create();
        $statuses = ['pending', 'accepted', 'delivered', 'cancelled'];
        
        foreach ($statuses as $status) {
            $donasi = DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Test Item',
                'status' => $status,
            ]);
            
            $this->assertEquals($status, $donasi->status);
        }
    }

    /**
     * Test DonasiBarang has kategori
     */
    public function test_donasi_barang_has_kategori()
    {
        $donatur = DonaturProfile::factory()->create();
        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Makanan',
            'kategori' => 'Pangan',
        ]);

        $this->assertEquals('Pangan', $donasi->kategori);
    }
}
