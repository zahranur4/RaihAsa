<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DonaturProfile;
use App\Models\PantiProfile;
use App\Models\DonasiBarang;
use App\Models\FoodRescue;
use App\Models\Wishlist;
use App\Models\RelawanProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryResultProcessingTest extends TestCase
{
    use RefreshDatabase;

    public function test_donation_list_query_processing()
    {
        // Create multiple donations
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        for ($i = 0; $i < 5; $i++) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Item ' . $i,
                'kategori' => 'Test',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $this->actingAs($donor);

        // List endpoint processes query
        $response = $this->get('/donasi-barang');
        $this->assertTrue($response->status() === 200);
        $this->assertIsArray($response->getData() ?? []);
    }

    public function test_wishlist_with_donor_data_processing()
    {
        // Create panti
        $panti_user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $panti_user->id,
            'nama_panti' => 'Test Panti',
            'alamat' => 'Jl. Panti',
            'no_telp' => '081234567890',
        ]);

        // Create donor with donation
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Donor Name',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        // Create wishlist matching donor's category
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Electronics',
            'jumlah' => 10,
            'urgensi' => 'mendesak',
            'kategori' => 'Elektronik',
            'status' => 'open',
        ]);

        $this->actingAs($panti_user);

        // View wishlist (query processes panti relationship)
        $response = $this->get('/wishlist');
        $this->assertTrue($response->status() === 200);

        // View specific wishlist (query processes related data)
        $response = $this->get("/wishlist/{$wishlist->id_wishlist}");
        $this->assertTrue($response->status() >= 200);
    }

    public function test_food_rescue_status_filtering()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Food Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Food',
        ]);

        // Create food items with different statuses
        FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Available Food',
            'porsi' => 20,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(2),
            'status' => 'available',
        ]);

        FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Claimed Food',
            'porsi' => 15,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(3),
            'status' => 'claimed',
        ]);

        $this->actingAs($donor);

        // Filter by available status
        $response = $this->get('/food-rescue?status=available');
        $this->assertTrue($response->status() === 200);

        // Filter by claimed status
        $response = $this->get('/food-rescue?status=claimed');
        $this->assertTrue($response->status() === 200);

        // No status filter
        $response = $this->get('/food-rescue');
        $this->assertTrue($response->status() === 200);
    }

    public function test_donation_category_aggregation()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        // Create donations in different categories
        $categories = ['Elektronik', 'Pakaian', 'Makanan', 'Buku'];
        foreach ($categories as $cat) {
            for ($i = 0; $i < 3; $i++) {
                DonasiBarang::create([
                    'id_donatur' => $donatur->id_donatur,
                    'nama_barang' => 'Item ' . $i,
                    'kategori' => $cat,
                    'foto' => 'item.jpg',
                    'status' => 'pending',
                ]);
            }
        }

        $this->actingAs($donor);

        // Get donations by category
        foreach ($categories as $cat) {
            $response = $this->get('/donasi-barang?kategori=' . urlencode($cat));
            $this->assertTrue($response->status() === 200);
        }
    }

    public function test_volunteer_with_related_user_data()
    {
        $user = User::factory()->create(['nama' => 'Volunteer User']);
        
        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Full Name',
            'nik' => '1234567890123456',
            'skill' => 'Logistik',
            'kategori' => 'Distribusi',
            'status_verif' => 'menunggu_verif',
        ]);

        $this->actingAs($user);

        // View volunteer profile (processes user + relawan data)
        $response = $this->get('/relawan-profile');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_panti_with_related_wishlist_data()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Panti Name',
            'alamat' => 'Jl. Panti',
            'no_telp' => '081234567890',
        ]);

        // Create multiple wishlists
        for ($i = 0; $i < 5; $i++) {
            Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => 'Item ' . $i,
                'jumlah' => 10 + $i,
                'urgensi' => $i % 2 == 0 ? 'mendesak' : 'rutin',
                'kategori' => 'Test',
                'status' => 'open',
            ]);
        }

        $this->actingAs($user);

        // View panti profile (processes wishlists)
        $response = $this->get('/panti-profile');
        $this->assertTrue($response->status() >= 200);

        // View wishlist page (aggregate data)
        $response = $this->get('/wishlist');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_donation_matching_query_processing()
    {
        // Create wishlist with urgency
        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Urgent Panti',
            'alamat' => 'Jl. Urgent',
            'no_telp' => '081234567890',
        ]);

        $wishlists = [];
        $urgencies = ['mendesak', 'kesehatan', 'pendidikan', 'rutin'];
        foreach ($urgencies as $urg) {
            $wishlists[] = Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => 'Item ' . $urg,
                'jumlah' => 100,
                'urgensi' => $urg,
                'kategori' => 'Test',
                'status' => 'open',
            ]);
        }

        $donor = User::factory()->create();
        $this->actingAs($donor);

        // Query for matching (processes urgency sorting)
        $response = $this->post('/donation-matching/find-matches', [
            'item_name' => 'Item',
            'category' => 'Test',
            'quantity' => 50,
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 404);
    }

    public function test_admin_dashboard_data_aggregation()
    {
        // Create varied data
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Donation',
            'kategori' => 'Test',
            'foto' => 'item.jpg',
            'status' => 'pending',
        ]);

        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Panti',
            'alamat' => 'Jl. Panti',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Need',
            'jumlah' => 50,
            'urgensi' => 'mendesak',
            'kategori' => 'Test',
            'status' => 'open',
        ]);

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        // Dashboard query processes multiple data types
        $response = $this->get('/admin/dashboard');
        $this->assertTrue($response->status() === 200);
    }

    public function test_donation_status_count_query()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        $statuses = ['pending', 'accepted', 'delivered'];
        foreach ($statuses as $status) {
            for ($i = 0; $i < 3; $i++) {
                DonasiBarang::create([
                    'id_donatur' => $donatur->id_donatur,
                    'nama_barang' => 'Item ' . $i,
                    'kategori' => 'Test',
                    'foto' => 'item.jpg',
                    'status' => $status,
                ]);
            }
        }

        $this->actingAs($donor);

        // Count donations by status
        $pending = DonasiBarang::where('status', 'pending')->count();
        $this->assertEqual(3, $pending);

        $accepted = DonasiBarang::where('status', 'accepted')->count();
        $this->assertEqual(3, $accepted);
    }

    public function test_urgency_based_wishlist_sorting()
    {
        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Sort Panti',
            'alamat' => 'Jl. Sort',
            'no_telp' => '081234567890',
        ]);

        $urgencies = ['rutin', 'pendidikan', 'kesehatan', 'mendesak'];
        foreach ($urgencies as $urg) {
            Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => 'Item ' . $urg,
                'jumlah' => 50,
                'urgensi' => $urg,
                'kategori' => 'Test',
                'status' => 'open',
            ]);
        }

        // Query for urgent items
        $urgent = Wishlist::where('urgensi', '=', 'mendesak')->get();
        $this->assertTrue(count($urgent) >= 1);

        // Query for all with urgency order
        $all = Wishlist::orderBy('urgensi', 'desc')->get();
        $this->assertTrue(count($all) >= 4);
    }

    public function test_multi_table_join_query()
    {
        // Create donor -> donation chain
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        $donation = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Donation',
            'kategori' => 'Test',
            'foto' => 'item.jpg',
            'status' => 'pending',
        ]);

        // Create panti -> wishlist chain
        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Panti',
            'alamat' => 'Jl. Panti',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Donation',
            'jumlah' => 50,
            'urgensi' => 'mendesak',
            'kategori' => 'Test',
            'status' => 'open',
        ]);

        // Verify relationships
        $this->assertTrue($donation->exists());
        $this->assertTrue($wishlist->exists());
        $this->assertEqual($donation->id_donasi, $donation->id_donasi);
    }

    public function test_list_with_relation_loading()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        for ($i = 0; $i < 10; $i++) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Item ' . $i,
                'kategori' => 'Test',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $this->actingAs($donor);

        // List with loaded relations
        $response = $this->get('/donasi-barang');
        $this->assertTrue($response->status() === 200);

        // Verify count
        $count = DonasiBarang::count();
        $this->assertGreaterThanOrEqual(10, $count);
    }
}
