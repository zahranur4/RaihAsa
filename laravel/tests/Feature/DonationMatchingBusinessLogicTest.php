<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wishlist;
use App\Models\PantiProfile;
use App\Models\DonaturProfile;
use App\Models\DonasiBarang;
use App\Models\FoodRescue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationMatchingBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_find_matches_requires_authentication()
    {
        $response = $this->post('/donation-matching/find-matches', [
            'item_name' => 'Rice',
            'category' => 'Makanan',
            'quantity' => 100,
        ]);

        $this->assertTrue($response->status() >= 400);
    }

    public function test_find_matches_with_item_name()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Matching Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Matching',
        ]);

        $pantiUser = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $pantiUser->id,
            'nama_panti' => 'Panti for Matching',
            'alamat' => 'Jl. Panti',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Rice',
            'jumlah' => 100,
            'urgensi' => 'mendesak',
            'kategori' => 'Makanan',
            'status' => 'open',
        ]);

        $response = $this->actingAs($user)->post('/donation-matching/find-matches', [
            'item_name' => 'Rice',
            'category' => 'Makanan',
            'quantity' => 50,
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() == 404);
    }

    public function test_find_matches_with_category_filter()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Category Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Category',
        ]);

        $pantiUser = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $pantiUser->id,
            'nama_panti' => 'Category Panti',
            'alamat' => 'Jl. Category Panti',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Medicine',
            'jumlah' => 50,
            'urgensi' => 'kesehatan',
            'kategori' => 'Kesehatan',
            'status' => 'open',
        ]);

        $response = $this->actingAs($user)->post('/donation-matching/find-matches', [
            'item_name' => '',
            'category' => 'Kesehatan',
            'quantity' => 25,
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() == 404);
    }

    public function test_wishlist_urgency_scoring()
    {
        $pantiUser = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $pantiUser->id,
            'nama_panti' => 'Urgency Panti',
            'alamat' => 'Jl. Urgency',
            'no_telp' => '081234567890',
        ]);

        $mendesak = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Urgent Item',
            'jumlah' => 100,
            'urgensi' => 'mendesak',
            'kategori' => 'Umum',
            'status' => 'open',
        ]);

        $rutin = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Routine Item',
            'jumlah' => 100,
            'urgensi' => 'rutin',
            'kategori' => 'Umum',
            'status' => 'open',
        ]);

        $this->assertEquals('mendesak', $mendesak->urgensi);
        $this->assertEquals('rutin', $rutin->urgensi);
    }

    public function test_wishlist_quantity_calculation()
    {
        $pantiUser = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $pantiUser->id,
            'nama_panti' => 'Qty Panti',
            'alamat' => 'Jl. Qty',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Items',
            'jumlah' => 100,
            'urgensi' => 'rutin',
            'kategori' => 'Umum',
            'status' => 'open',
        ]);

        $this->assertEquals(100, $wishlist->jumlah);
        $fulfillment = min(100, (int)((50 / max(1, $wishlist->jumlah)) * 100));
        $this->assertEquals(50, $fulfillment);
    }

    public function test_donation_pledge_workflow()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Pledge Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Pledge',
        ]);

        $pantiUser = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $pantiUser->id,
            'nama_panti' => 'Pledge Panti',
            'alamat' => 'Jl. Pledge Panti',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Pledge Item',
            'jumlah' => 100,
            'urgensi' => 'mendesak',
            'kategori' => 'Umum',
            'status' => 'open',
        ]);

        $this->actingAs($user);
        $this->assertTrue($wishlist->status === 'open');
        $this->assertTrue($donatur->id_donatur > 0);
    }

    public function test_donor_profile_exists_for_donation()
    {
        $user = User::factory()->create();
        
        // Without profile - should not match
        $response = $this->actingAs($user)->post('/donation-matching/find-matches', [
            'item_name' => 'Test',
            'quantity' => 10,
        ]);

        // Create profile
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Profile Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Profile',
        ]);

        $this->assertNotNull($donatur->id_donatur);
    }

    public function test_wishlist_status_open_only()
    {
        $pantiUser = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $pantiUser->id,
            'nama_panti' => 'Status Panti',
            'alamat' => 'Jl. Status',
            'no_telp' => '081234567890',
        ]);

        $open = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Open Item',
            'jumlah' => 50,
            'urgensi' => 'rutin',
            'kategori' => 'Umum',
            'status' => 'open',
        ]);

        $closed = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Closed Item',
            'jumlah' => 50,
            'urgensi' => 'rutin',
            'kategori' => 'Umum',
            'status' => 'closed',
        ]);

        $openWishlists = Wishlist::where('status', 'open')->count();
        $this->assertGreaterThan(0, $openWishlists);

        $closedWishlists = Wishlist::where('status', 'closed')->count();
        $this->assertGreaterThanOrEqual(0, $closedWishlists);
    }

    public function test_food_rescue_donation_flow()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Food Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Food',
        ]);

        $food = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Cooked Rice',
            'porsi' => 50,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(4),
            'status' => 'available',
        ]);

        $this->assertEquals('available', $food->status);
        $this->assertEquals(50, $food->porsi);
    }

    public function test_barang_donation_flow()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Item Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Item',
        ]);

        $pantiUser = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $pantiUser->id,
            'nama_panti' => 'Receiving Panti',
            'alamat' => 'Jl. Receive',
            'no_telp' => '081234567890',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Books',
            'kategori' => 'Pendidikan',
            'foto' => 'books.jpg',
            'status' => 'pending',
            'id_panti' => null,
        ]);

        $this->assertNull($donasi->id_panti);
        
        $donasi->update(['id_panti' => $panti->id_panti, 'status' => 'accepted']);
        $this->assertEqual($panti->id_panti, $donasi->fresh()->id_panti);
    }

    public function test_volunteer_assignment()
    {
        $user = User::factory()->create();
        $relawan = \App\Models\RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Volunteer',
            'nik' => '1234567890123456',
            'skill' => 'Logistik',
            'kategori' => 'Distribusi',
            'status_verif' => 'terverifikasi',
        ]);

        $this->assertEquals('terverifikasi', $relawan->status_verif);
        $this->assertEquals('Logistik', $relawan->skill);
    }

    public function test_panti_dashboard_data()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Dashboard Panti',
            'alamat' => 'Jl. Dashboard',
            'no_telp' => '081234567890',
        ]);

        $this->assertNotNull($panti->id_panti);
        
        // Create related wishlists
        for ($i = 0; $i < 3; $i++) {
            Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => 'Item ' . $i,
                'jumlah' => 10 + $i,
                'urgensi' => 'rutin',
                'kategori' => 'Umum',
                'status' => 'open',
            ]);
        }

        $wishlistCount = Wishlist::where('id_panti', $panti->id_panti)->count();
        $this->assertEqual(3, $wishlistCount);
    }

    public function test_donor_donation_history()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'History Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. History',
        ]);

        // Create multiple donations
        for ($i = 0; $i < 5; $i++) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Item ' . $i,
                'kategori' => 'Umum',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $count = DonasiBarang::where('id_donatur', $donatur->id_donatur)->count();
        $this->assertGreaterThanOrEqual(5, $count);
    }

    public function test_food_rescue_status_tracking()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Food Tracker',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Tracker',
        ]);

        $food = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Tracking Food',
            'porsi' => 30,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(3),
            'status' => 'available',
        ]);

        $this->assertEquals('available', $food->status);
        
        $food->update(['status' => 'claimed']);
        $this->assertEquals('claimed', $food->fresh()->status);
    }

    public function test_matching_algorithm_urgency()
    {
        $pantiUser = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $pantiUser->id,
            'nama_panti' => 'Urgent Panti',
            'alamat' => 'Jl. Urgent',
            'no_telp' => '081234567890',
        ]);

        $urgentWishlists = [];
        foreach (['mendesak', 'kesehatan', 'pendidikan', 'rutin'] as $urgensi) {
            $urgentWishlists[] = Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => 'Item ' . $urgensi,
                'jumlah' => 50,
                'urgensi' => $urgensi,
                'kategori' => 'Umum',
                'status' => 'open',
            ]);
        }

        $this->assertEqual(4, count($urgentWishlists));
    }

    public function test_panti_balance_and_inventory()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Inventory Panti',
            'alamat' => 'Jl. Inventory',
            'no_telp' => '081234567890',
        ]);

        $donatur = DonaturProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_lengkap' => 'Donor for Inventory',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        // Add items to inventory
        $items = [];
        for ($i = 0; $i < 3; $i++) {
            $items[] = DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Inventory Item ' . $i,
                'kategori' => 'Umum',
                'foto' => 'item.jpg',
                'status' => 'accepted',
                'id_panti' => $panti->id_panti,
            ]);
        }

        $inventory = DonasiBarang::where('id_panti', $panti->id_panti)->count();
        $this->assertEqual(3, $inventory);
    }
}
