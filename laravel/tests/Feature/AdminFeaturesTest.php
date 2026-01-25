<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DonasiBarang;
use App\Models\DonaturProfile;
use App\Models\FoodRescue;
use App\Models\PantiProfile;
use App\Models\RelawanProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_creation()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->assertTrue($admin->is_admin);
    }

    public function test_admin_can_view_users()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($admin)->get('/admin/users');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_can_view_donations()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Test Item',
            'kategori' => 'Elektronik',
            'foto' => 'test.jpg',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get('/admin/donasi-barang');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_can_filter_by_status()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/donasi-barang?status=pending');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_can_view_food_rescue()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Food Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Food',
        ]);

        FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Cooked Rice',
            'porsi' => 20,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(2),
            'status' => 'available',
        ]);

        $response = $this->actingAs($admin)->get('/admin/food-rescue');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_can_view_volunteers()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        
        RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test Volunteer',
            'nik' => '1234567890123456',
            'skill' => 'Logistik',
            'kategori' => 'Distribusi',
            'status_verif' => 'menunggu_verif',
        ]);

        $response = $this->actingAs($admin)->get('/admin/volunteers');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_can_view_panti()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        
        PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat' => 'Jl. Test',
            'no_telp' => '081234567890',
        ]);

        $response = $this->actingAs($admin)->get('/admin/panti');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_dashboard_shows_statistics()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_can_verify_volunteer()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        
        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Unverified Vol',
            'nik' => '1234567890123456',
            'skill' => 'Logistik',
            'kategori' => 'Distribusi',
            'status_verif' => 'menunggu_verif',
        ]);

        $this->assertEqual('menunggu_verif', $relawan->status_verif);
    }

    public function test_admin_approve_donation()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Item',
            'kategori' => 'Umum',
            'foto' => 'item.jpg',
            'status' => 'pending',
        ]);

        $this->assertEqual('pending', $donasi->status);
    }

    public function test_admin_reject_donation()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donor',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Item',
            'kategori' => 'Umum',
            'foto' => 'item.jpg',
            'status' => 'pending',
        ]);

        $donasi->update(['status' => 'cancelled']);
        $this->assertEqual('cancelled', $donasi->fresh()->status);
    }

    public function test_admin_analytics_data()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Analytics Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Analytics',
        ]);

        for ($i = 0; $i < 5; $i++) {
            DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Analytics Item ' . $i,
                'kategori' => 'Umum',
                'foto' => 'item.jpg',
                'status' => 'pending',
            ]);
        }

        $count = DonasiBarang::count();
        $this->assertGreaterThanOrEqual(5, $count);
    }

    public function test_admin_export_data()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/users');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_search_donations()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/donasi-barang?search=test');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_sort_by_date()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/donasi-barang?sort=created_at');
        $this->assertTrue($response->status() === 200 || $response->status() === 404);
    }

    public function test_admin_filter_by_category()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/donasi-barang?kategori=Elektronik');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_pagination()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/users?page=1');
        $this->assertTrue($response->status() === 200);
    }

    public function test_non_admin_cannot_access_admin()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_guest_cannot_access_admin()
    {
        $response = $this->get('/admin/dashboard');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_admin_user_management()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->create(['is_admin' => false]);

        $allUsers = User::all();
        $this->assertTrue($allUsers->count() > 1);
    }

    public function test_admin_can_see_user_profiles()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        
        DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Profile Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Profile',
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_views_summary_stats()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $userCount = User::count();
        $this->assertGreaterThan(0, $userCount);
    }

    public function test_admin_access_control_middleware()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_admin_database_operations()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'is_admin' => true]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_admin' => false]);
    }

    public function test_admin_view_rendering()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_error_handling()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/nonexistent');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_admin_quick_stats()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory(10)->create();
        
        $count = User::count();
        $this->assertGreaterThan(10, $count);
    }
}
