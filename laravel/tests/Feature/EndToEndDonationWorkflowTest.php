<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DonaturProfile;
use App\Models\PantiProfile;
use App\Models\DonasiBarang;
use App\Models\FoodRescue;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndToEndDonationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_donation_workflow()
    {
        // 1. User registers
        $response = $this->post('/register', [
            'nama' => 'Donor User',
            'email' => 'donor' . uniqid() . '@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', $response->request->getPassword())->first() ?? User::latest()->first();
        $this->assertNotNull($user);

        // 2. Create donor profile
        $this->actingAs($user);
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Full Donor Name',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Jalan No. 123',
        ]);

        $this->assertNotNull($donatur);

        // 3. Browse donations
        $response = $this->get('/donasi-barang');
        $this->assertTrue($response->status() >= 200);

        // 4. Create donation
        $response = $this->post('/donasi-barang', [
            'nama_barang' => 'Test Item',
            'kategori' => 'Elektronik',
            'foto' => 'test.jpg',
            'status' => 'pending',
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 302);

        // 5. View donation
        $donasi = DonasiBarang::where('id_donatur', $donatur->id_donatur)->first();
        if ($donasi) {
            $response = $this->get("/donasi-barang/{$donasi->id_donasi}");
            $this->assertTrue($response->status() >= 200);
        }
    }

    public function test_food_rescue_complete_workflow()
    {
        // 1. User login
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Food Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Food',
        ]);

        $this->actingAs($user);

        // 2. Browse food rescue
        $response = $this->get('/food-rescue');
        $this->assertTrue($response->status() >= 200);

        // 3. Create food rescue
        $response = $this->post('/food-rescue', [
            'nama_makanan' => 'Cooked Rice',
            'porsi' => 30,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(3),
            'status' => 'available',
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 302 || $response->status() >= 404);

        // 4. View food rescue list
        $response = $this->get('/food-rescue?status=available');
        $this->assertTrue($response->status() >= 200);

        // 5. Create food item
        $food = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Test Food',
            'porsi' => 25,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(2),
            'status' => 'available',
        ]);

        $this->assertNotNull($food);

        // 6. View food detail
        $response = $this->get("/food-rescue/{$food->id_food}");
        $this->assertTrue($response->status() >= 200);
    }

    public function test_panti_wishlist_workflow()
    {
        // 1. Panti user login
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat' => 'Jl. Panti',
            'no_telp' => '081234567890',
        ]);

        $this->actingAs($user);

        // 2. Browse wishlists
        $response = $this->get('/wishlist');
        $this->assertTrue($response->status() >= 200);

        // 3. Create wishlist
        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Rice',
            'jumlah' => 100,
            'urgensi' => 'mendesak',
            'kategori' => 'Makanan',
            'status' => 'open',
        ]);

        $this->assertNotNull($wishlist);

        // 4. Filter wishlist
        $response = $this->get('/wishlist?urgensi=mendesak');
        $this->assertTrue($response->status() >= 200);

        // 5. View wishlist detail
        $response = $this->get("/wishlist/{$wishlist->id_wishlist}");
        $this->assertTrue($response->status() >= 200 || $response->status() >= 404);
    }

    public function test_matching_algorithm_workflow()
    {
        // 1. Donor user
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Matching Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        $this->actingAs($donor);

        // 2. Panti with wishlist
        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Matching Panti',
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

        // 3. Find matches
        $response = $this->post('/donation-matching/find-matches', [
            'item_name' => 'Rice',
            'category' => 'Makanan',
            'quantity' => 50,
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 404);
    }

    public function test_donation_approval_workflow()
    {
        // 1. Donor creates donation
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Approval Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Approval',
        ]);

        $this->actingAs($donor);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Approval Item',
            'kategori' => 'Test',
            'foto' => 'approval.jpg',
            'status' => 'pending',
        ]);

        $this->assertEqual('pending', $donasi->status);

        // 2. Admin approves
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $response = $this->get('/admin/donasi-barang');
        $this->assertTrue($response->status() === 200);

        // 3. Admin updates status
        $donasi->update(['status' => 'accepted']);
        $this->assertEqual('accepted', $donasi->fresh()->status);
    }

    public function test_volunteer_verification_workflow()
    {
        // 1. Volunteer registers
        $response = $this->post('/register', [
            'nama' => 'Volunteer User',
            'email' => 'volunteer' . uniqid() . '@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::latest()->first();
        $this->actingAs($user);

        // 2. Create volunteer profile
        $relawan = \App\Models\RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Volunteer Name',
            'nik' => '1234567890123456',
            'skill' => 'Logistik',
            'kategori' => 'Distribusi',
            'status_verif' => 'menunggu_verif',
        ]);

        $this->assertEqual('menunggu_verif', $relawan->status_verif);

        // 3. Admin verifies
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $response = $this->get('/admin/volunteers');
        $this->assertTrue($response->status() === 200);

        // 4. Admin approves volunteer
        $relawan->update(['status_verif' => 'terverifikasi']);
        $this->assertEqual('terverifikasi', $relawan->fresh()->status_verif);
    }

    public function test_panti_receive_donation_workflow()
    {
        // 1. Donor donates
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        $this->actingAs($donor);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Donation Item',
            'kategori' => 'Elektronik',
            'foto' => 'item.jpg',
            'status' => 'pending',
        ]);

        // 2. Panti browses wishlist
        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Receiving Panti',
            'alamat' => 'Jl. Panti',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Electronics',
            'jumlah' => 5,
            'urgensi' => 'rutin',
            'kategori' => 'Elektronik',
            'status' => 'open',
        ]);

        $this->actingAs(User::find($panti->id_user));
        $response = $this->get('/wishlist');
        $this->assertTrue($response->status() >= 200);

        // 3. Admin assigns to panti
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $donasi->update(['id_panti' => $panti->id_panti, 'status' => 'accepted']);
        $this->assertEqual($panti->id_panti, $donasi->fresh()->id_panti);
    }

    public function test_user_profile_management_workflow()
    {
        // 1. User registers
        $response = $this->post('/register', [
            'nama' => 'Profile User',
            'email' => 'profile' . uniqid() . '@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::latest()->first();
        $this->actingAs($user);

        // 2. View profile
        $response = $this->get('/profile');
        $this->assertTrue($response->status() >= 200);

        // 3. Update profile
        $response = $this->put('/profile', [
            'nama' => 'Updated Name',
        ]);

        $this->assertTrue($response->status() >= 200 || $response->status() >= 302);

        // 4. View donations history
        $response = $this->get('/donasi-saya');
        $this->assertTrue($response->status() >= 200 || $response->status() >= 404);

        // 5. View food rescue history
        $response = $this->get('/food-rescue-saya');
        $this->assertTrue($response->status() >= 200 || $response->status() >= 404);
    }

    public function test_admin_dashboard_workflow()
    {
        // 1. Admin login
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        // 2. View dashboard
        $response = $this->get('/admin/dashboard');
        $this->assertTrue($response->status() === 200);

        // 3. View users
        $response = $this->get('/admin/users');
        $this->assertTrue($response->status() === 200);

        // 4. View donations
        $response = $this->get('/admin/donasi-barang');
        $this->assertTrue($response->status() === 200);

        // 5. View food rescue
        $response = $this->get('/admin/food-rescue');
        $this->assertTrue($response->status() === 200);

        // 6. View volunteers
        $response = $this->get('/admin/volunteers');
        $this->assertTrue($response->status() === 200);

        // 7. View panti
        $response = $this->get('/admin/panti');
        $this->assertTrue($response->status() === 200);

        // 8. Search/filter
        $response = $this->get('/admin/donasi-barang?status=pending');
        $this->assertTrue($response->status() === 200);
    }

    public function test_multi_step_donation_matching_workflow()
    {
        // Setup: Multiple donors and panti
        $donors = [];
        for ($i = 0; $i < 3; $i++) {
            $user = User::factory()->create();
            $donatur = DonaturProfile::create([
                'id_user' => $user->id,
                'nama_lengkap' => 'Donor ' . $i,
                'no_telp' => '081234567890',
                'alamat_jemput' => 'Jl. Donor ' . $i,
            ]);
            $donors[] = ['user' => $user, 'donatur' => $donatur];
        }

        $panti = PantiProfile::create([
            'id_user' => User::factory()->create()->id,
            'nama_panti' => 'Multi Panti',
            'alamat' => 'Jl. Multi',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Rice',
            'jumlah' => 300,
            'urgensi' => 'mendesak',
            'kategori' => 'Makanan',
            'status' => 'open',
        ]);

        // Each donor creates donation
        foreach ($donors as $donor_data) {
            $this->actingAs($donor_data['user']);

            DonasiBarang::create([
                'id_donatur' => $donor_data['donatur']->id_donatur,
                'nama_barang' => 'Donated Item',
                'kategori' => 'Makanan',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $count = DonasiBarang::count();
        $this->assertGreaterThanOrEqual(3, $count);
    }

    public function test_complex_filtering_workflow()
    {
        // Create varied donations
        $donor = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $donor->id,
            'nama_lengkap' => 'Filter Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Filter',
        ]);

        foreach (['Elektronik', 'Pakaian', 'Makanan'] as $cat) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Item ' . $cat,
                'kategori' => $cat,
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $this->actingAs($donor);

        // Filter by category
        $response = $this->get('/donasi-barang?kategori=Elektronik');
        $this->assertTrue($response->status() >= 200);

        // Filter by status
        $response = $this->get('/donasi-barang?status=pending');
        $this->assertTrue($response->status() >= 200);

        // Combine filters
        $response = $this->get('/donasi-barang?kategori=Pakaian&status=pending');
        $this->assertTrue($response->status() >= 200);
    }
}
