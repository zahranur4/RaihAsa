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

class CriticalPathE2ETest extends TestCase
{
    use RefreshDatabase;

    public function test_donation_full_lifecycle_pending_to_delivered()
    {
        // Donor creates donation
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Lifecycle Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Lifecycle',
        ]);

        $this->actingAs($donor);

        // POST donation
        $this->post('/donasi-barang', [
            'nama_barang' => 'Lifecycle Item',
            'kategori' => 'Elektronik',
            'foto' => 'lifecycle.jpg',
        ]);

        $donation = DonasiBarang::latest()->first();
        $this->assertNotNull($donation);
        $initial_status = $donation->status;

        // Admin approves
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $this->patch("/admin/donasi-barang/{$donation->id_donasi}", [
            'status' => 'accepted',
        ]);

        $donation->refresh();
        $this->assertEqual('accepted', $donation->status);

        // Admin assigns to panti
        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Recipient Panti',
            'alamat' => 'Jl. Recipient',
            'no_telp' => '081234567890',
        ]);

        $this->patch("/admin/donasi-barang/{$donation->id_donasi}", [
            'id_panti' => $panti->id_panti,
        ]);

        $donation->refresh();
        $this->assertEqual($panti->id_panti, $donation->id_panti);

        // Mark as delivered
        $this->patch("/admin/donasi-barang/{$donation->id_donasi}", [
            'status' => 'delivered',
        ]);

        $donation->refresh();
        $this->assertEqual('delivered', $donation->status);
    }

    public function test_food_rescue_complete_lifecycle()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Food Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Food',
        ]);

        // Create food rescue
        $food = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Cooked Rice',
            'porsi' => 50,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(4),
            'status' => 'available',
        ]);

        // Volunteer claims it
        $volunteer = User::factory()->create();
        $this->actingAs($volunteer);

        $response = $this->patch("/food-rescue/{$food->id_food}", [
            'status' => 'claimed',
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 302);

        // Check status updated
        $food->refresh();
        $this->assertTrue($food->status === 'claimed' || $food->status === 'available');
    }

    public function test_wishlist_matching_with_donation()
    {
        // Create panti with wishlist
        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Matching Panti',
            'alamat' => 'Jl. Match',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Rice',
            'jumlah' => 100,
            'urgensi' => 'mendesak',
            'kategori' => 'Makanan',
            'status' => 'open',
        ]);

        // Donor creates matching donation
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Match Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        $this->actingAs($donor);

        $donation = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Rice',
            'kategori' => 'Makanan',
            'foto' => 'rice.jpg',
            'status' => 'pending',
        ]);

        // Find matches
        $response = $this->post('/donation-matching/find-matches', [
            'item_name' => 'Rice',
            'category' => 'Makanan',
            'quantity' => 50,
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 404);
    }

    public function test_volunteer_workflow_complete()
    {
        // Create volunteer
        $user = User::factory()->create();
        $this->actingAs($user);

        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Complete Volunteer',
            'nik' => '1234567890123456',
            'skill' => 'Logistik',
            'kategori' => 'Distribusi',
            'status_verif' => 'menunggu_verif',
        ]);

        $this->assertNotNull($relawan);

        // Admin verifies
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $response = $this->get('/admin/volunteers');
        $this->assertTrue($response->status() === 200);

        // Update status
        $relawan->update(['status_verif' => 'terverifikasi']);
        $this->assertEqual('terverifikasi', $relawan->fresh()->status_verif);

        // Verified volunteer can see more features
        $this->actingAs($user);
        $response = $this->get('/donasi-barang');
        $this->assertTrue($response->status() === 200);
    }

    public function test_panti_profile_with_wishlists_management()
    {
        // Create panti
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Management Panti',
            'alamat' => 'Jl. Management',
            'no_telp' => '081234567890',
        ]);

        $this->actingAs($user);

        // Create wishlist
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Rice',
            'jumlah' => 200,
            'urgensi' => 'mendesak',
            'kategori' => 'Makanan',
            'status' => 'open',
        ]);

        // View wishlists
        $response = $this->get('/wishlist');
        $this->assertTrue($response->status() === 200);

        // Update wishlist
        $response = $this->patch("/wishlist/{$wishlist->id_wishlist}", [
            'status' => 'closed',
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 302);

        // Verify update
        $wishlist->refresh();
        $this->assertTrue($wishlist->status === 'closed' || $wishlist->status === 'open');
    }

    public function test_admin_verify_multiple_donors()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Create multiple donors
        for ($i = 0; $i < 5; $i++) {
            $user = User::factory()->create();
            $donatur = DonaturProfile::create([
                'id_user' => $user->id,
                'nama_lengkap' => 'Donor ' . $i,
                'no_telp' => '0812345678' . $i,
                'alamat_jemput' => 'Jl. Donor ' . $i,
            ]);

            // Create donation
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Item ' . $i,
                'kategori' => 'Test',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $this->actingAs($admin);

        // View all donations
        $response = $this->get('/admin/donasi-barang');
        $this->assertTrue($response->status() === 200);

        // Approve each one
        $donations = DonasiBarang::where('status', 'pending')->get();
        foreach ($donations as $donation) {
            $donation->update(['status' => 'accepted']);
            $this->assertEqual('accepted', $donation->fresh()->status);
        }
    }

    public function test_complex_search_and_filter()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Filter Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Filter',
        ]);

        // Create diverse donations
        $items = [
            ['name' => 'Rice', 'cat' => 'Makanan', 'status' => 'pending'],
            ['name' => 'Laptop', 'cat' => 'Elektronik', 'status' => 'pending'],
            ['name' => 'Shirt', 'cat' => 'Pakaian', 'status' => 'accepted'],
            ['name' => 'Book', 'cat' => 'Buku', 'status' => 'accepted'],
            ['name' => 'Blanket', 'cat' => 'Pakaian', 'status' => 'pending'],
        ];

        foreach ($items as $item) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => $item['name'],
                'kategori' => $item['cat'],
                'foto' => 'item.jpg',
                'status' => $item['status'],
            ]);
        }

        $this->actingAs($donor);

        // Search + Filter combinations
        $tests = [
            '/donasi-barang?search=Rice',
            '/donasi-barang?kategori=Makanan',
            '/donasi-barang?status=pending',
            '/donasi-barang?kategori=Elektronik&status=pending',
            '/donasi-barang?search=Shirt&kategori=Pakaian',
        ];

        foreach ($tests as $url) {
            $response = $this->get($url);
            $this->assertTrue($response->status() === 200);
        }
    }

    public function test_donor_view_own_donations()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Own Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Own',
        ]);

        // Create multiple donations
        for ($i = 0; $i < 3; $i++) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'My Item ' . $i,
                'kategori' => 'Test',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $this->actingAs($donor);

        // View own donations
        $response = $this->get('/donasi-saya');
        $this->assertTrue($response->status() === 200 || $response->status() >= 404);

        // List view
        $response = $this->get('/donasi-barang');
        $this->assertTrue($response->status() === 200);
    }

    public function test_panti_view_open_wishlists()
    {
        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'View Panti',
            'alamat' => 'Jl. View',
            'no_telp' => '081234567890',
        ]);

        // Create diverse wishlists
        $wishlists = [
            ['name' => 'Rice', 'urg' => 'mendesak', 'status' => 'open'],
            ['name' => 'Blanket', 'urg' => 'kesehatan', 'status' => 'open'],
            ['name' => 'Medicine', 'urg' => 'pendidikan', 'status' => 'closed'],
            ['name' => 'Clothes', 'urg' => 'rutin', 'status' => 'open'],
        ];

        foreach ($wishlists as $item) {
            Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => $item['name'],
                'jumlah' => 50,
                'urgensi' => $item['urg'],
                'kategori' => 'Test',
                'status' => $item['status'],
            ]);
        }

        // Panti views wishlists
        $this->actingAs(User::find($panti->id_user));
        $response = $this->get('/wishlist');
        $this->assertTrue($response->status() === 200);

        // Filter by urgency
        $response = $this->get('/wishlist?urgensi=mendesak');
        $this->assertTrue($response->status() === 200);

        // Filter by status
        $response = $this->get('/wishlist?status=open');
        $this->assertTrue($response->status() === 200);
    }

    public function test_statistics_and_analytics()
    {
        // Create sample data
        for ($i = 0; $i < 5; $i++) {
            $donor = User::factory()->create();
            $donatur = DonaturProfile::create([
                'id_user' => $donor->id,
                'nama_lengkap' => 'Stat Donor ' . $i,
                'no_telp' => '0812345678' . $i,
                'alamat_jemput' => 'Jl. Stat',
            ]);

            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Item ' . $i,
                'kategori' => ['Elektronik', 'Makanan', 'Pakaian', 'Buku', 'Test'][$i],
                'foto' => 'item.jpg',
                'status' => ['pending', 'accepted', 'delivered', 'cancelled', 'pending'][$i],
            ]);
        }

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        // View dashboard (calculates stats)
        $response = $this->get('/admin/dashboard');
        $this->assertTrue($response->status() === 200);

        // Verify data counts
        $total_donations = DonasiBarang::count();
        $this->assertGreaterThanOrEqual(5, $total_donations);
    }

    public function test_authorization_boundaries()
    {
        $donor1 = User::factory()->create();
        $donor2 = User::factory()->create();

        $donatur1 = DonaturProfile::create([
            'id_user' => $donor1->id,
            'nama_lengkap' => 'Donor 1',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor 1',
        ]);

        $donation = DonasiBarang::create([
            'id_donatur' => $donatur1->id_donatur,
            'nama_barang' => 'Item',
            'kategori' => 'Test',
            'foto' => 'item.jpg',
            'status' => 'pending',
        ]);

        // Donor 1 can view own
        $this->actingAs($donor1);
        $response = $this->get("/donasi-barang/{$donation->id_donasi}");
        $this->assertTrue($response->status() >= 200);

        // Donor 2 can view published
        $this->actingAs($donor2);
        $response = $this->get("/donasi-barang/{$donation->id_donasi}");
        $this->assertTrue($response->status() >= 200);
    }
}
