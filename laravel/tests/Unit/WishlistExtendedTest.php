<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Wishlist;
use App\Models\PantiProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WishlistExtendedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Wishlist can be deleted
     */
    public function test_wishlist_can_be_deleted()
    {
        $panti = PantiProfile::factory()->create();
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'nama_barang' => 'Test Item',
            'kategori' => 'Test',
            'jumlah' => 10,
            'urgensi' => 'medium',
            'status' => 'open',
        ]);

        $id = $wishlist->id_wishlist;
        $wishlist->delete();

        $this->assertNull(Wishlist::find($id));
    }

    /**
     * Test Wishlist has status field
     */
    public function test_wishlist_has_status_field()
    {
        $panti = PantiProfile::factory()->create();
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'nama_barang' => 'Test Item',
            'kategori' => 'Test',
            'jumlah' => 10,
            'status' => 'open',
        ]);

        $this->assertEquals('open', $wishlist->status);
    }

    /**
     * Test Wishlist has urgensi field
     */
    public function test_wishlist_has_urgensi_field()
    {
        $panti = PantiProfile::factory()->create();
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'nama_barang' => 'Test Item',
            'kategori' => 'Test',
            'jumlah' => 10,
            'urgensi' => 'medium',
        ]);

        $this->assertEquals('medium', $wishlist->urgensi);
    }

    /**
     * Test Wishlist belongs to Panti
     */
    public function test_wishlist_belongs_to_panti()
    {
        $panti = PantiProfile::factory()->create();
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'nama_barang' => 'Test Item',
            'kategori' => 'Test',
            'jumlah' => 10,
        ]);

        $this->assertNotNull($wishlist->panti);
        $this->assertEquals($panti->id_panti, $wishlist->panti->id_panti);
    }

    /**
     * Test Wishlist has image attribute
     */
    public function test_wishlist_can_have_image()
    {
        $panti = PantiProfile::factory()->create();
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'nama_barang' => 'Test Item',
            'kategori' => 'Test',
            'jumlah' => 10,
            'image' => 'path/to/image.jpg',
        ]);

        $this->assertEquals('path/to/image.jpg', $wishlist->image);
    }
}
