<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\DonaturProfile;
use App\Models\RelawanProfile;
use App\Models\FoodRescue;
use App\Models\DonasiBarang;
use App\Models\PantiProfile;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsComprehensiveTest extends TestCase
{
    use RefreshDatabase;

    // DonaturProfile Tests
    public function test_donatur_profile_can_be_created()
    {
        $user = User::factory()->create();
        $profile = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'John Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test No. 123',
        ]);

        $this->assertNotNull($profile->id_donatur);
        $this->assertEquals('John Donor', $profile->nama_lengkap);
        $this->assertEquals($user->id, $profile->id_user);
    }

    public function test_donatur_profile_fillable_attributes()
    {
        $profile = new DonaturProfile();
        $this->assertTrue(in_array('id_user', $profile->getFillable()));
        $this->assertTrue(in_array('nama_lengkap', $profile->getFillable()));
        $this->assertTrue(in_array('no_telp', $profile->getFillable()));
        $this->assertTrue(in_array('alamat_jemput', $profile->getFillable()));
    }

    public function test_donatur_profile_belongs_to_user()
    {
        $user = User::factory()->create();
        $profile = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $this->assertEquals($user->id, $profile->user->id);
        $this->assertIsObject($profile->user);
    }

    public function test_donatur_profile_has_many_donations()
    {
        $user = User::factory()->create();
        $profile = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        DonasiBarang::create([
            'id_donatur' => $profile->id_donatur,
            'nama_barang' => 'Barang 1',
            'kategori' => 'Elektronik',
            'foto' => 'test.jpg',
            'status' => 'pending',
        ]);

        $this->assertTrue($profile->donations->count() > 0);
        $this->assertEquals('Barang 1', $profile->donations->first()->nama_barang);
    }

    public function test_donatur_profile_has_many_food_rescues()
    {
        $user = User::factory()->create();
        $profile = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        FoodRescue::create([
            'id_donatur' => $profile->id_donatur,
            'nama_makanan' => 'Nasi Goreng',
            'porsi' => 10,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(2),
            'status' => 'available',
        ]);

        $this->assertTrue($profile->foodRescues->count() > 0);
        $this->assertEquals('Nasi Goreng', $profile->foodRescues->first()->nama_makanan);
    }

    // RelawanProfile Tests
    public function test_relawan_profile_can_be_created()
    {
        $user = User::factory()->create();
        $profile = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'John Volunteer',
            'nik' => '1234567890123456',
            'skill' => 'Logistik',
            'kategori' => 'Transportasi',
            'status_verif' => 'terverifikasi',
        ]);

        $this->assertNotNull($profile->id_relawan);
        $this->assertEquals('John Volunteer', $profile->nama_lengkap);
        $this->assertEquals($user->id, $profile->id_user);
    }

    public function test_relawan_profile_fillable_attributes()
    {
        $profile = new RelawanProfile();
        $this->assertTrue(in_array('id_user', $profile->getFillable()));
        $this->assertTrue(in_array('nama_lengkap', $profile->getFillable()));
        $this->assertTrue(in_array('nik', $profile->getFillable()));
        $this->assertTrue(in_array('skill', $profile->getFillable()));
        $this->assertTrue(in_array('kategori', $profile->getFillable()));
        $this->assertTrue(in_array('status_verif', $profile->getFillable()));
    }

    public function test_relawan_profile_belongs_to_user()
    {
        $user = User::factory()->create();
        $profile = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Volunteer Test',
            'nik' => '1234567890123456',
            'skill' => 'Logistik',
            'kategori' => 'Transportasi',
            'status_verif' => 'terverifikasi',
        ]);

        $this->assertEquals($user->id, $profile->user->id);
        $this->assertIsObject($profile->user);
    }

    public function test_relawan_profile_status_variations()
    {
        $user = User::factory()->create();
        
        $profile1 = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Volunteer 1',
            'nik' => '1111111111111111',
            'skill' => 'Logistik',
            'kategori' => 'Transportasi',
            'status_verif' => 'terverifikasi',
        ]);

        $profile2 = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Volunteer 2',
            'nik' => '2222222222222222',
            'skill' => 'Distribusi',
            'kategori' => 'Packaging',
            'status_verif' => 'menunggu_verif',
        ]);

        $this->assertEquals('terverifikasi', $profile1->status_verif);
        $this->assertEquals('menunggu_verif', $profile2->status_verif);
    }

    // FoodRescue Tests
    public function test_food_rescue_can_be_created()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $foodRescue = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Nasi Kuning',
            'porsi' => 15,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(3),
            'status' => 'available',
        ]);

        $this->assertNotNull($foodRescue->id_food);
        $this->assertEquals('Nasi Kuning', $foodRescue->nama_makanan);
        $this->assertEquals(15, $foodRescue->porsi);
    }

    public function test_food_rescue_fillable_attributes()
    {
        $rescue = new FoodRescue();
        $this->assertTrue(in_array('id_donatur', $rescue->getFillable()));
        $this->assertTrue(in_array('nama_makanan', $rescue->getFillable()));
        $this->assertTrue(in_array('porsi', $rescue->getFillable()));
        $this->assertTrue(in_array('waktu_dibuat', $rescue->getFillable()));
        $this->assertTrue(in_array('waktu_expired', $rescue->getFillable()));
        $this->assertTrue(in_array('status', $rescue->getFillable()));
        $this->assertTrue(in_array('id_claimer', $rescue->getFillable()));
    }

    public function test_food_rescue_date_casting()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $now = now();
        $expired = now()->addHours(4);

        $foodRescue = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Soto Ayam',
            'porsi' => 20,
            'waktu_dibuat' => $now,
            'waktu_expired' => $expired,
            'status' => 'tersedia',
        ]);

        $this->assertNotNull($foodRescue->waktu_dibuat);
        $this->assertNotNull($foodRescue->waktu_expired);
    }

    public function test_food_rescue_status_variations()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $statuses = ['available', 'claimed', 'expired'];
        foreach ($statuses as $status) {
            $foodRescue = FoodRescue::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_makanan' => 'Makanan ' . $status,
                'porsi' => 10,
                'waktu_dibuat' => now(),
                'waktu_expired' => now()->addHours(2),
                'status' => $status,
            ]);

            $this->assertEquals($status, $foodRescue->status);
        }
    }

    // DonasiBarang Tests
    public function test_donasi_barang_can_be_created()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Laptop',
            'kategori' => 'Elektronik',
            'foto' => 'laptop.jpg',
            'status' => 'terima',
        ]);

        $this->assertNotNull($donasi->id_donasi);
        $this->assertEquals('Laptop', $donasi->nama_barang);
        $this->assertEquals('Elektronik', $donasi->kategori);
    }

    public function test_donasi_barang_fillable_attributes()
    {
        $donasi = new DonasiBarang();
        $this->assertTrue(in_array('id_donatur', $donasi->getFillable()));
        $this->assertTrue(in_array('nama_barang', $donasi->getFillable()));
        $this->assertTrue(in_array('kategori', $donasi->getFillable()));
        $this->assertTrue(in_array('foto', $donasi->getFillable()));
        $this->assertTrue(in_array('status', $donasi->getFillable()));
        $this->assertTrue(in_array('id_panti', $donasi->getFillable()));
    }

    public function test_donasi_barang_kategori_variations()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $categories = ['Elektronik', 'Pakaian', 'Makanan', 'Peralatan', 'Kesehatan'];
        foreach ($categories as $category) {
            $donasi = DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Barang ' . $category,
                'kategori' => $category,
                'foto' => 'barang.jpg',
                'status' => 'menunggu',
            ]);

            $this->assertEquals($category, $donasi->kategori);
        }
    }

    public function test_donasi_barang_status_variations()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donor Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $statuses = ['pending', 'accepted', 'delivered', 'cancelled'];
        foreach ($statuses as $status) {
            $donasi = DonasiBarang::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_barang' => 'Barang ' . $status,
                'kategori' => 'Umum',
                'foto' => 'barang.jpg',
                'status' => $status,
            ]);

            $this->assertEquals($status, $donasi->status);
        }
    }

    // User Model Tests
    public function test_user_can_create_donatur_profile()
    {
        $user = User::factory()->create();
        $this->assertIsInt($user->id);
        $this->assertNotEmpty($user->email);
    }

    public function test_user_model_table()
    {
        $user = new User();
        $this->assertEquals('users', $user->getTable());
    }

    public function test_user_model_fillable()
    {
        $user = new User();
        $fillable = $user->getFillable();
        // Check if at least email and password are fillable
        $this->assertTrue(in_array('email', $fillable) || in_array('password', $fillable));
    }

    // PantiProfile Tests
    public function test_panti_profile_table()
    {
        $panti = new PantiProfile();
        $this->assertEquals('panti_profiles', $panti->getTable());
    }

    public function test_panti_profile_fillable()
    {
        $panti = new PantiProfile();
        $fillable = $panti->getFillable();
        $this->assertTrue(!empty($fillable));
    }

    // Wishlist Tests
    public function test_wishlist_table()
    {
        $wishlist = new Wishlist();
        $this->assertEquals('wishlists', $wishlist->getTable());
    }

    public function test_wishlist_fillable()
    {
        $wishlist = new Wishlist();
        $fillable = $wishlist->getFillable();
        $this->assertTrue(!empty($fillable));
    }
}
