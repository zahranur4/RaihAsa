<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\DonaturProfile;
use App\Models\RelawanProfile;
use App\Models\PantiProfile;
use App\Models\DonasiBarang;
use App\Models\FoodRescue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrationDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_complete_donatur_record()
    {
        $user = User::factory()->create([
            'email' => 'donatur' . time() . '@test.com',
            'nama' => 'Test Donatur',
        ]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertTrue($user->id > 0);
        $this->assertNotEmpty($user->email);
    }

    public function test_create_complete_relawan_record()
    {
        $user = User::factory()->create([
            'email' => 'relawan' . time() . '@test.com',
            'nama' => 'Test Relawan',
        ]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertTrue($user->id > 0);
    }

    public function test_create_complete_panti_record()
    {
        $user = User::factory()->create([
            'email' => 'panti' . time() . '@test.com',
            'nama' => 'Test Panti',
        ]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertTrue($user->id > 0);
    }

    public function test_donasi_barang_relationships()
    {
        $user = User::factory()->create([
            'email' => 'donor' . uniqid() . '@test.com',
        ]);

        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'John Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $donasi = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Test Item',
            'kategori' => 'Elektronik',
            'foto' => 'test.jpg',
            'status' => 'pending',
        ]);

        $this->assertEquals($donatur->id_donatur, $donasi->id_donatur);
        $this->assertDatabaseHas('donasi_barang', ['id_donasi' => $donasi->id_donasi]);
    }

    public function test_food_rescue_relationships()
    {
        $user = User::factory()->create([
            'email' => 'fooddonor' . uniqid() . '@test.com',
        ]);

        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'John Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $foodRescue = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Nasi Goreng',
            'porsi' => 10,
            'waktu_dibuat' => now(),
            'waktu_expired' => now()->addHours(2),
            'status' => 'available',
        ]);

        $this->assertEquals($donatur->id_donatur, $foodRescue->id_donatur);
        $this->assertDatabaseHas('food_rescue', ['id_food' => $foodRescue->id_food]);
    }

    public function test_model_timestamps()
    {
        $user = User::factory()->create([
            'email' => 'timestamp' . uniqid() . '@test.com',
        ]);

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }

    public function test_donatur_profile_timestamps()
    {
        $user = User::factory()->create([
            'email' => 'profile' . uniqid() . '@test.com',
        ]);

        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $this->assertNotNull($donatur->created_at);
        $this->assertNotNull($donatur->updated_at);
    }

    public function test_database_persistence()
    {
        $user = User::factory()->create([
            'email' => 'persist' . uniqid() . '@test.com',
        ]);

        $found = User::find($user->id);
        $this->assertEquals($user->id, $found->id);
        $this->assertEquals($user->email, $found->email);
    }

    public function test_model_mass_assignment()
    {
        $data = [
            'nama_lengkap' => 'Test Donatur',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test Street',
        ];

        $user = User::factory()->create([
            'email' => 'mass' . uniqid() . '@test.com',
        ]);

        $donatur = DonaturProfile::create(array_merge(['id_user' => $user->id], $data));
        $this->assertEquals('Test Donatur', $donatur->nama_lengkap);
    }

    public function test_enum_status_field()
    {
        $user = User::factory()->create([
            'email' => 'enum' . uniqid() . '@test.com',
        ]);

        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $donasi1 = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Item 1',
            'kategori' => 'Elektronik',
            'status' => 'pending',
        ]);

        $donasi2 = DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Item 2',
            'kategori' => 'Pakaian',
            'status' => 'accepted',
        ]);

        $this->assertEquals('pending', $donasi1->status);
        $this->assertEquals('accepted', $donasi2->status);
    }

    public function test_multiple_records_creation()
    {
        for ($i = 0; $i < 5; $i++) {
            User::factory()->create([
                'email' => 'bulk' . $i . '_' . uniqid() . '@test.com',
            ]);
        }

        $count = User::count();
        $this->assertGreaterThanOrEqual(5, $count);
    }

    public function test_datetime_fields()
    {
        $user = User::factory()->create([
            'email' => 'datetime' . uniqid() . '@test.com',
        ]);

        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $now = now();
        $foodRescue = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Food',
            'porsi' => 5,
            'waktu_dibuat' => $now,
            'waktu_expired' => $now->addHours(3),
            'status' => 'available',
        ]);

        $this->assertNotNull($foodRescue->waktu_dibuat);
        $this->assertNotNull($foodRescue->waktu_expired);
    }

    public function test_model_querying()
    {
        $user1 = User::factory()->create([
            'email' => 'query1_' . uniqid() . '@test.com',
        ]);
        $user2 = User::factory()->create([
            'email' => 'query2_' . uniqid() . '@test.com',
        ]);

        $found = User::where('id', $user1->id)->first();
        $this->assertEquals($user1->id, $found->id);
    }

    public function test_model_update()
    {
        $user = User::factory()->create([
            'email' => 'update_' . uniqid() . '@test.com',
            'nama' => 'Original Name',
        ]);

        $user->update(['nama' => 'Updated Name']);
        $this->assertEquals('Updated Name', $user->nama);
    }

    public function test_model_deletion()
    {
        $user = User::factory()->create([
            'email' => 'delete_' . uniqid() . '@test.com',
        ]);

        $id = $user->id;
        $user->delete();
        $found = User::find($id);
        $this->assertNull($found);
    }

    public function test_all_models_instantiate()
    {
        $this->assertNotNull(new User());
        $this->assertNotNull(new DonaturProfile());
        $this->assertNotNull(new RelawanProfile());
        $this->assertNotNull(new PantiProfile());
        $this->assertNotNull(new DonasiBarang());
        $this->assertNotNull(new FoodRescue());
    }

    public function test_model_primary_keys()
    {
        $user = new User();
        $donatur = new DonaturProfile();
        $relawan = new RelawanProfile();
        
        $this->assertEquals('id', $user->getKeyName());
        $this->assertEquals('id_donatur', $donatur->getKeyName());
        $this->assertEquals('id_relawan', $relawan->getKeyName());
    }
}
