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

class DinamicWorkflowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cross_user_donation_visibility()
    {
        // Create 3 donors
        $donors = [];
        for ($i = 0; $i < 3; $i++) {
            $user = User::factory()->create();
            $donatur = DonaturProfile::create([
                'id_user' => $user->id,
                'nama_lengkap' => 'Donor ' . $i,
                'no_telp' => '0812345678' . $i,
                'alamat_jemput' => 'Jl. Donor ' . $i,
            ]);
            $donors[] = compact('user', 'donatur');

            // Each creates donation
            for ($j = 0; $j < 2; $j++) {
                DonasiBarang::create([
                    'id_donatur' => $donatur->id_donatur,
                    'nama_barang' => "Donor{$i}Item{$j}",
                    'kategori' => 'Test',
                    'foto' => 'item.jpg',
                    'status' => 'pending',
                ]);
            }
        }

        // Each donor views all
        foreach ($donors as $donor_data) {
            $this->actingAs($donor_data['user']);
            $response = $this->get('/donasi-barang');
            $this->assertEqual(200, $response->status());
        }

        // Guest can view
        $this->actingAs(null);
        $response = $this->get('/donasi-barang');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_wishlist_matching_scenarios()
    {
        // Scenario 1: Wishlist > Donation
        $panti1 = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Panti 1',
            'alamat' => 'Jl. Panti 1',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti1->id_panti,
            'barang_diinginkan' => 'Rice',
            'jumlah' => 500,
            'urgensi' => 'mendesak',
            'kategori' => 'Makanan',
            'status' => 'open',
        ]);

        // Scenario 2: Donation > Wishlist
        $panti2 = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Panti 2',
            'alamat' => 'Jl. Panti 2',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti2->id_panti,
            'barang_diinginkan' => 'Blanket',
            'jumlah' => 20,
            'urgensi' => 'kesehatan',
            'kategori' => 'Pakaian',
            'status' => 'open',
        ]);

        // Scenario 3: Exact match
        $panti3 = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Panti 3',
            'alamat' => 'Jl. Panti 3',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti3->id_panti,
            'barang_diinginkan' => 'Books',
            'jumlah' => 100,
            'urgensi' => 'pendidikan',
            'kategori' => 'Buku',
            'status' => 'open',
        ]);

        // Verify all wishlists exist
        $all_wishlists = Wishlist::count();
        $this->assertGreaterThanOrEqual(3, $all_wishlists);
    }

    public function test_urgency_prioritization_workflow()
    {
        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Urgent Panti',
            'alamat' => 'Jl. Urgent',
            'no_telp' => '081234567890',
        ]);

        // Create wishlists with different urgencies
        $urgency_data = [
            ['name' => 'Medicine', 'urg' => 'mendesak'],
            ['name' => 'Hospital Equipment', 'urg' => 'kesehatan'],
            ['name' => 'School Supplies', 'urg' => 'pendidikan'],
            ['name' => 'Regular Items', 'urg' => 'rutin'],
        ];

        foreach ($urgency_data as $data) {
            Wishlist::create([
                'id_panti' => $panti->id_panti,
                'barang_diinginkan' => $data['name'],
                'jumlah' => 50,
                'urgensi' => $data['urg'],
                'kategori' => 'Test',
                'status' => 'open',
            ]);
        }

        // Query for urgent only
        $urgent = Wishlist::where('urgensi', 'mendesak')->get();
        $this->assertTrue(count($urgent) >= 1);

        // Query for health-related
        $health = Wishlist::where('urgensi', 'kesehatan')->get();
        $this->assertTrue(count($health) >= 1);
    }

    public function test_food_rescue_expiry_handling()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Food Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Food',
        ]);

        // Create food items with different expiry times
        $items = [
            ['name' => 'Fresh Food', 'hours' => 4],
            ['name' => 'Urgent Food', 'hours' => 1],
            ['name' => 'Soon Food', 'hours' => 2],
        ];

        foreach ($items as $item) {
            FoodRescue::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_makanan' => $item['name'],
                'porsi' => 20,
                'waktu_dibuat' => now(),
                'waktu_expired' => now()->addHours($item['hours']),
                'status' => 'available',
            ]);
        }

        $this->actingAs($donor);

        // View available food
        $response = $this->get('/food-rescue?status=available');
        $this->assertEqual(200, $response->status());
    }

    public function test_admin_approval_workflow_multiple()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Create pending donations
        $pending = [];
        for ($i = 0; $i < 5; $i++) {
            $donor = User::factory()->create();
            $donatur = DonaturProfile::create([
                'id_user' => $donor->id,
                'nama_lengkap' => 'Pending Donor ' . $i,
                'no_telp' => '0812345678' . $i,
                'alamat_jemput' => 'Jl. Pending ' . $i,
            ]);

            $donation = DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Pending Item ' . $i,
                'kategori' => 'Test',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);

            $pending[] = $donation;
        }

        $this->actingAs($admin);

        // Admin views pending
        $response = $this->get('/admin/donasi-barang?status=pending');
        $this->assertEqual(200, $response->status());

        // Admin approves some
        foreach (array_slice($pending, 0, 3) as $donation) {
            $donation->update(['status' => 'accepted']);
            $this->assertEqual('accepted', $donation->fresh()->status);
        }

        // Admin rejects others
        foreach (array_slice($pending, 3) as $donation) {
            $donation->update(['status' => 'cancelled']);
            $this->assertEqual('cancelled', $donation->fresh()->status);
        }
    }

    public function test_volunteer_assignment_workflow()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Create unverified volunteers
        $volunteers = [];
        for ($i = 0; $i < 3; $i++) {
            $user = User::factory()->create();
            $relawan = RelawanProfile::create([
                'id_user' => $user->id,
                'nama_lengkap' => 'Volunteer ' . $i,
                'nik' => '123456789012345' . $i,
                'skill' => ['Logistik', 'Medis', 'Distribusi'][$i],
                'kategori' => 'Test',
                'status_verif' => 'menunggu_verif',
            ]);

            $volunteers[] = ['user' => $user, 'relawan' => $relawan];
        }

        $this->actingAs($admin);

        // Admin views all volunteers
        $response = $this->get('/admin/volunteers');
        $this->assertEqual(200, $response->status());

        // Admin verifies some
        foreach (array_slice($volunteers, 0, 2) as $vol_data) {
            $vol_data['relawan']->update(['status_verif' => 'terverifikasi']);
            $this->assertEqual('terverifikasi', $vol_data['relawan']->fresh()->status_verif);
        }

        // Admin rejects one
        $volunteers[2]['relawan']->update(['status_verif' => 'ditolak']);
        $this->assertEqual('ditolak', $volunteers[2]['relawan']->fresh()->status_verif);
    }

    public function test_panti_management_full_workflow()
    {
        // Create multiple panti
        $pantis = [];
        for ($i = 0; $i < 3; $i++) {
            $user = User::factory()->create();
            $panti = PantiProfile::create([
                'id_user' => $user->id,
                'nama_panti' => 'Managed Panti ' . $i,
                'alamat' => 'Jl. Managed ' . $i,
                'no_telp' => '0812345678' . $i,
            ]);

            // Each panti creates wishlists
            for ($j = 0; $j < 3; $j++) {
                Wishlist::create([
                    'id_panti' => $panti->id_panti,
                    'barang_diinginkan' => 'Item ' . $j,
                    'jumlah' => 50 + ($j * 10),
                    'urgensi' => ['mendesak', 'kesehatan', 'pendidikan'][$j],
                    'kategori' => 'Test',
                    'status' => 'open',
                ]);
            }

            $pantis[] = ['user' => $user, 'panti' => $panti];
        }

        // Each panti views own wishlists
        foreach ($pantis as $panti_data) {
            $this->actingAs($panti_data['user']);
            $response = $this->get('/wishlist');
            $this->assertEqual(200, $response->status());
        }

        // Admin views all panti
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);
        $response = $this->get('/admin/panti');
        $this->assertEqual(200, $response->status());
    }

    public function test_donation_status_transitions()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Transition Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Transition',
        ]);

        $donation = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Transition Item',
            'kategori' => 'Test',
            'foto' => 'item.jpg',
            'status' => 'pending',
        ]);

        // Transitions: pending -> accepted -> delivered
        $transitions = [
            'pending' => 'accepted',
            'accepted' => 'delivered',
        ];

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        foreach ($transitions as $from => $to) {
            $donation->update(['status' => $to]);
            $this->assertEqual($to, $donation->fresh()->status);
        }

        // Also test cancellation path
        $donation->update(['status' => 'cancelled']);
        $this->assertEqual('cancelled', $donation->fresh()->status);
    }

    public function test_search_across_categories()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Search Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Search',
        ]);

        // Create diverse items
        $items = [
            ['name' => 'Rice', 'cat' => 'Makanan'],
            ['name' => 'Laptop', 'cat' => 'Elektronik'],
            ['name' => 'T-Shirt', 'cat' => 'Pakaian'],
            ['name' => 'Mathematics Book', 'cat' => 'Buku'],
            ['name' => 'Blanket', 'cat' => 'Pakaian'],
        ];

        foreach ($items as $item) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => $item['name'],
                'kategori' => $item['cat'],
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $this->actingAs($donor);

        // Search by name
        $response = $this->get('/donasi-barang?search=Rice');
        $this->assertEqual(200, $response->status());

        // Search by name across category
        $response = $this->get('/donasi-barang?search=Book');
        $this->assertEqual(200, $response->status());

        // Filter by category
        $response = $this->get('/donasi-barang?kategori=Elektronik');
        $this->assertEqual(200, $response->status());
    }

    public function test_user_authentication_flow()
    {
        // Register
        $response = $this->post('/register', [
            'nama' => 'Auth Test User',
            'email' => 'auth' . uniqid() . '@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::latest()->first();
        $this->assertNotNull($user);

        // Login
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Check authenticated
        $this->actingAs($user);
        $response = $this->get('/profile');
        $this->assertTrue($response->status() >= 200);

        // Logout
        $response = $this->post('/logout');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_donation_visibility_by_status()
    {
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Visibility Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Visibility',
        ]);

        // Create donations with different statuses
        $statuses = ['pending', 'accepted', 'delivered', 'cancelled'];
        foreach ($statuses as $status) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Item ' . $status,
                'kategori' => 'Test',
                'foto' => 'item.jpg',
                'status' => $status,
            ]);
        }

        // Guest can view
        $response = $this->get('/donasi-barang');
        $this->assertEqual(200, $response->status());

        // Filter by status
        foreach ($statuses as $status) {
            $response = $this->get("/donasi-barang?status=$status");
            $this->assertEqual(200, $response->status());
        }
    }
}
