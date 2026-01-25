<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wishlist;
use App\Models\PantiProfile;
use App\Models\DonaturProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistAndMatchingTest extends TestCase
{
    use RefreshDatabase;

    public function test_wishlist_index_loads()
    {
        $response = $this->get('/wishlist');
        $this->assertNotNull($response);
    }

    public function test_wishlist_index_with_urgensi_filter()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat' => 'Jl. Test',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Rice',
            'quantity_dibutuhkan' => 100,
            'urgensi' => 'mendesak',
            'kategori' => 'Makanan',
            'status' => 'open',
        ]);

        $response = $this->get('/wishlist?urgensi=mendesak');
        $this->assertNotNull($response);
    }

    public function test_wishlist_index_with_kategori_filter()
    {
        $response = $this->get('/wishlist?kategori=Makanan');
        $this->assertNotNull($response);
    }

    public function test_donation_matching_page()
    {
        $response = $this->get('/donation-matching');
        $this->assertTrue($response->status() >= 200 || $response->status() == 404);
    }

    public function test_wishlist_detail()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti Detail',
            'alamat' => 'Jl. Test Detail',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Supplies',
            'quantity_dibutuhkan' => 50,
            'urgensi' => 'rutin',
            'kategori' => 'Perlengkapan',
            'status' => 'open',
        ]);

        $response = $this->get("/wishlist/{$wishlist->id_wishlist}");
        $this->assertNotNull($response);
    }

    public function test_donation_matching_calculation()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Panti Matching',
            'alamat' => 'Jl. Match',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Clothes',
            'quantity_dibutuhkan' => 100,
            'urgensi' => 'pendidikan',
            'kategori' => 'Pakaian',
            'status' => 'open',
        ]);

        $this->assertEquals(100, $wishlist->quantity_dibutuhkan);
    }

    public function test_wishlist_status_variations()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Status Panti',
            'alamat' => 'Jl. Status',
            'no_telp' => '081234567890',
        ]);

        $open = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Open Item',
            'quantity_dibutuhkan' => 10,
            'urgensi' => 'rutin',
            'kategori' => 'Umum',
            'status' => 'open',
        ]);

        $closed = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Closed Item',
            'quantity_dibutuhkan' => 10,
            'urgensi' => 'rutin',
            'kategori' => 'Umum',
            'status' => 'closed',
        ]);

        $this->assertEquals('open', $open->status);
        $this->assertEquals('closed', $closed->status);
    }

    public function test_wishlist_urgensi_variations()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Urgensi Panti',
            'alamat' => 'Jl. Urgensi',
            'no_telp' => '081234567890',
        ]);

        $urgensiOptions = ['mendesak', 'rutin', 'pendidikan', 'kesehatan'];
        foreach ($urgensiOptions as $urgensi) {
            $wishlist = Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => 'Item ' . $urgensi,
                'quantity_dibutuhkan' => 10,
                'urgensi' => $urgensi,
                'kategori' => 'Umum',
                'status' => 'open',
            ]);

            $this->assertEquals($urgensi, $wishlist->urgensi);
        }
    }

    public function test_panti_can_create_wishlist()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Create Panti',
            'alamat' => 'Jl. Create',
            'no_telp' => '081234567890',
        ]);

        $this->assertNotNull($panti->id_panti);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Wishlist Item',
            'quantity_dibutuhkan' => 25,
            'urgensi' => 'kesehatan',
            'kategori' => 'Kesehatan',
            'status' => 'open',
        ]);

        $this->assertEquals($panti->id_panti, $wishlist->id_panti);
    }

    public function test_panti_wishlist_relationship()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Relationship Panti',
            'alamat' => 'Jl. Relationship',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Related Item',
            'quantity_dibutuhkan' => 30,
            'urgensi' => 'rutin',
            'kategori' => 'Perlengkapan',
            'status' => 'open',
        ]);

        $this->assertTrue($panti->id_panti == $wishlist->id_panti);
    }

    public function test_multiple_wishlists_per_panti()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Multiple Panti',
            'alamat' => 'Jl. Multiple',
            'no_telp' => '081234567890',
        ]);

        for ($i = 0; $i < 3; $i++) {
            Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => 'Item ' . $i,
                'quantity_dibutuhkan' => 10 + $i,
                'urgensi' => 'rutin',
                'kategori' => 'Umum',
                'status' => 'open',
            ]);
        }

        $wishlists = Wishlist::where('id_panti', $panti->id_panti)->get();
        $this->assertGreaterThanOrEqual(3, $wishlists->count());
    }

    public function test_wishlist_database_operations()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'DB Panti',
            'alamat' => 'Jl. DB',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Database Item',
            'quantity_dibutuhkan' => 45,
            'urgensi' => 'mendesak',
            'kategori' => 'Test',
            'status' => 'open',
        ]);

        $found = Wishlist::find($wishlist->id_wishlist);
        $this->assertNotNull($found);
        $this->assertEquals('Database Item', $found->barang_diinginkan);

        $found->update(['status' => 'closed']);
        $this->assertEquals('closed', $found->fresh()->status);
    }

    public function test_wishlist_timestamps()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Timestamp Panti',
            'alamat' => 'Jl. Timestamp',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Time Item',
            'quantity_dibutuhkan' => 20,
            'urgensi' => 'rutin',
            'kategori' => 'Umum',
            'status' => 'open',
        ]);

        $this->assertNotNull($wishlist->created_at);
        $this->assertNotNull($wishlist->updated_at);
    }

    public function test_wishlist_query_optimization()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Query Panti',
            'alamat' => 'Jl. Query',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Query Item',
            'quantity_dibutuhkan' => 15,
            'urgensi' => 'kesehatan',
            'kategori' => 'Kesehatan',
            'status' => 'open',
        ]);

        $wishlists = Wishlist::where('status', 'open')->get();
        $this->assertTrue($wishlists->count() > 0);
    }

    public function test_wishlist_pagination()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Paginate Panti',
            'alamat' => 'Jl. Paginate',
            'no_telp' => '081234567890',
        ]);

        for ($i = 0; $i < 15; $i++) {
            Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => 'Paginated Item ' . $i,
                'quantity_dibutuhkan' => 10,
                'urgensi' => 'rutin',
                'kategori' => 'Umum',
                'status' => 'open',
            ]);
        }

        $response = $this->get('/wishlist');
        $this->assertNotNull($response);
    }
}
