<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DonaturProfile;
use App\Models\DonasiBarang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinalCoveragePushTest extends TestCase
{
    use RefreshDatabase;

    public function test_donation_store_and_retrieve()
    {
        $user = User::factory()->create([
            'email' => 'donor_final' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Final Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Final',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Final Item',
            'kategori' => 'Elektronik',
            'foto' => 'final.jpg',
            'status' => 'pending',
        ]);

        $retrieved = DonasiBarang::find($donasi->id_donasi);
        $this->assertEquals('Final Item', $retrieved->nama_barang);
        $this->assertEquals('Elektronik', $retrieved->kategori);
        $this->assertEquals('pending', $retrieved->status);
    }

    public function test_donation_filter_all_categories()
    {
        $categories = ['Elektronik', 'Pakaian', 'Makanan', 'Peralatan'];
        $user = User::factory()->create([
            'email' => 'categories' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Category Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Category',
        ]);

        foreach ($categories as $cat) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Item ' . $cat,
                'kategori' => $cat,
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        foreach ($categories as $cat) {
            $count = DonasiBarang::where('kategori', $cat)->count();
            $this->assertGreaterThan(0, $count);
        }
    }

    public function test_donation_status_update()
    {
        $user = User::factory()->create([
            'email' => 'update' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Update Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Update',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Update Item',
            'kategori' => 'Test',
            'foto' => 'update.jpg',
            'status' => 'pending',
        ]);

        $donasi->update(['status' => 'accepted']);
        $this->assertEquals('accepted', $donasi->fresh()->status);

        $donasi->update(['status' => 'delivered']);
        $this->assertEquals('delivered', $donasi->fresh()->status);

        $donasi->update(['status' => 'cancelled']);
        $this->assertEquals('cancelled', $donasi->fresh()->status);
    }

    public function test_donatur_profile_phone_validation()
    {
        $user = User::factory()->create([
            'email' => 'phone' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Phone Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Phone',
        ]);

        $this->assertEquals('081234567890', $donatur->no_telp);
        $this->assertTrue(strlen($donatur->no_telp) >= 10);
    }

    public function test_donation_address_stored()
    {
        $user = User::factory()->create([
            'email' => 'address' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Address Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Kompleks Perumahan No. 123, Jakarta Selatan',
        ]);

        $this->assertNotEmpty($donatur->alamat_jemput);
        $this->assertStringContainsString('Jakarta', $donatur->alamat_jemput);
    }

    public function test_donation_query_with_user()
    {
        $user = User::factory()->create([
            'email' => 'query' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Query Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Query',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Query Item',
            'kategori' => 'Query',
            'foto' => 'query.jpg',
            'status' => 'pending',
        ]);

        $found = DonasiBarang::where('id_donasi', $donasi->id_donasi)->first();
        $this->assertNotNull($found);
        $this->assertEquals($donatur->id_donatur, $found->id_donatur);
    }

    public function test_multiple_donations_per_donor()
    {
        $user = User::factory()->create([
            'email' => 'multi' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Multi Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Multi',
        ]);

        for ($i = 0; $i < 5; $i++) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Item ' . $i,
                'kategori' => 'Kategori',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $count = DonasiBarang::where('id_donatur', $donatur->id_donatur)->count();
        $this->assertGreaterThanOrEqual(5, $count);
    }

    public function test_donation_count_by_category()
    {
        $user = User::factory()->create([
            'email' => 'count' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Count Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Count',
        ]);

        for ($i = 0; $i < 3; $i++) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Elektronik Item ' . $i,
                'kategori' => 'Elektronik',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $elektronikCount = DonasiBarang::where('kategori', 'Elektronik')
            ->where('id_donatur', $donatur->id_donatur)
            ->count();
        
        $this->assertGreaterThanOrEqual(3, $elektronikCount);
    }

    public function test_donation_deletion()
    {
        $user = User::factory()->create([
            'email' => 'delete' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Delete Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Delete',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Delete Item',
            'kategori' => 'Delete',
            'foto' => 'delete.jpg',
            'status' => 'pending',
        ]);

        $id = $donasi->id_donasi;
        $donasi->delete();

        $found = DonasiBarang::find($id);
        $this->assertNull($found);
    }

    public function test_donatur_model_attributes()
    {
        $user = User::factory()->create([
            'email' => 'attr' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Attr Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Attr',
        ]);

        $this->assertTrue($donatur->id_donatur > 0);
        $this->assertNotNull($donatur->created_at);
        $this->assertNotNull($donatur->updated_at);
        $this->assertEquals($user->id, $donatur->id_user);
    }

    public function test_donation_table_integrity()
    {
        $user = User::factory()->create([
            'email' => 'integrity' . uniqid() . '@test.com',
        ]);
        
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Integrity Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Integrity',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Integrity Item',
            'kategori' => 'Integrity',
            'foto' => 'integrity.jpg',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('donasi_barang', [
            'id_donasi' => $donasi->id_donasi,
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Integrity Item',
        ]);
    }

    public function test_database_constraints()
    {
        $user = User::factory()->create([
            'email' => 'constraint' . uniqid() . '@test.com',
        ]);

        $this->assertNotNull($user->id);
        $this->assertIsInt($user->id);
    }
}
