<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Wishlist;
use App\Models\PantiProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Wishlist can be created
     */
    public function test_wishlist_can_be_created()
    {
        $panti = PantiProfile::factory()->create();
        
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'nama_barang' => 'Kebutuhan makanan',
            'kategori' => 'Pangan',
            'urgensi' => 'mendesak',
            'status' => 'open',
            'jumlah' => 50,
        ]);

        $this->assertNotNull($wishlist->id_wishlist);
        $this->assertEquals('Pangan', $wishlist->kategori);
    }

    /**
     * Test Wishlist status values
     */
    public function test_wishlist_status_values()
    {
        $panti = PantiProfile::factory()->create();
        $statuses = ['open', 'fulfilled', 'cancelled'];
        
        foreach ($statuses as $status) {
            $wishlist = Wishlist::create([
                'id_panti' => $panti->id_panti,
                'nama_barang' => 'Test Wishlist',
                'kategori' => 'Test',
                'urgensi' => 'medium',
                'status' => $status,
                'jumlah' => 10,
            ]);
            
            $this->assertEquals($status, $wishlist->status);
        }
    }

    /**
     * Test Wishlist urgensi values
     */
    public function test_wishlist_urgensi_values()
    {
        $panti = PantiProfile::factory()->create();
        $urgencies = ['low', 'medium', 'high'];
        
        foreach ($urgencies as $urgensi) {
            $wishlist = Wishlist::create([
                'id_panti' => $panti->id_panti,
                'nama_barang' => 'Test',
                'kategori' => 'Test',
                'urgensi' => $urgensi,
                'status' => 'open',
                'jumlah' => 10,
            ]);
            
            $this->assertEquals($urgensi, $wishlist->urgensi);
        }
    }

    /**
     * Test Wishlist quantity tracking
     */
    public function test_wishlist_jumlah_attribute()
    {
        $panti = PantiProfile::factory()->create();
        
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'nama_barang' => 'Test',
            'kategori' => 'Test',
            'urgensi' => 'medium',
            'status' => 'open',
            'jumlah' => 100,
        ]);

        $this->assertEquals(100, $wishlist->jumlah);
    }

    /**
     * Test Wishlist can be updated
     */
    public function test_wishlist_can_be_updated()
    {
        $panti = PantiProfile::factory()->create();
        
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'nama_barang' => 'Nama Lama',
            'kategori' => 'Test',
            'urgensi' => 'medium',
            'status' => 'open',
            'jumlah' => 50,
        ]);

        $wishlist->update([
            'status' => 'fulfilled',
            'nama_barang' => 'Nama Baru',
        ]);

        $this->assertEquals('Nama Baru', $wishlist->nama_barang);
        $this->assertEquals('fulfilled', $wishlist->status);
    }

    /**
     * Test Wishlist fillable attributes
     */
    public function test_wishlist_fillable_attributes()
    {
        $wishlist = new Wishlist();
        $expectedFillable = [
            'id_panti',
            'nama_barang',
            'kategori',
            'jumlah',
            'urgensi',
            'status',
            'image',
        ];

        foreach ($expectedFillable as $attr) {
            $this->assertContains($attr, $wishlist->getFillable());
        }
    }
}
