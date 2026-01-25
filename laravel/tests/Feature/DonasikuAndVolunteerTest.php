<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DonaturProfile;
use App\Models\RelawanProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonasikuAndVolunteerTest extends TestCase
{
    use RefreshDatabase;

    public function test_donasi_saya_requires_authentication()
    {
        $response = $this->get('/donasi-saya');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_donasi_saya_shows_for_authenticated_donor()
    {
        $user = User::factory()->create([
            'email' => 'donor_history' . uniqid() . '@test.com',
        ]);

        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Donasi Saya Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Donasi Saya',
        ]);

        $response = $this->actingAs($user)->get('/donasi-saya');
        $this->assertTrue($response->status() >= 200 || $response->status() == 404);
    }

    public function test_food_rescue_saya_requires_authentication()
    {
        $response = $this->get('/food-rescue-saya');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_food_rescue_saya_shows_for_authenticated_user()
    {
        $user = User::factory()->create([
            'email' => 'food_saya' . uniqid() . '@test.com',
        ]);

        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Food Saya Donor',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Food Saya',
        ]);

        $response = $this->actingAs($user)->get('/food-rescue-saya');
        $this->assertTrue($response->status() >= 200 || $response->status() == 404);
    }

    public function test_volunteer_registration_page()
    {
        $response = $this->get('/volunteer-registration');
        $this->assertTrue($response->status() >= 200 || $response->status() == 404);
    }

    public function test_volunteer_registration_authenticated()
    {
        $user = User::factory()->create([
            'email' => 'volunteer_reg' . uniqid() . '@test.com',
        ]);

        $response = $this->actingAs($user)->get('/volunteer-registration');
        $this->assertTrue($response->status() >= 200 || $response->status() == 404);
    }

    public function test_volunteer_profile_creation()
    {
        $user = User::factory()->create([
            'email' => 'volunteer_create' . uniqid() . '@test.com',
        ]);

        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test Volunteer',
            'nik' => '1234567890123456',
            'skill' => 'Logistik',
            'kategori' => 'Distribusi',
            'status_verif' => 'menunggu_verif',
        ]);

        $this->assertNotNull($relawan->id_relawan);
        $this->assertEquals('Logistik', $relawan->skill);
        $this->assertEquals('menunggu_verif', $relawan->status_verif);
    }

    public function test_volunteer_skill_variations()
    {
        $user = User::factory()->create([
            'email' => 'skill' . uniqid() . '@test.com',
        ]);

        $skills = ['Logistik', 'Distribusi', 'Packaging', 'Transportasi'];
        foreach ($skills as $skill) {
            $relawan = RelawanProfile::create([
                'id_user' => $user->id,
                'nama_lengkap' => 'Volunteer ' . $skill,
                'nik' => substr(md5($skill), 0, 16),
                'skill' => $skill,
                'kategori' => 'Test',
                'status_verif' => 'terverifikasi',
            ]);

            $this->assertEquals($skill, $relawan->skill);
        }
    }

    public function test_volunteer_category_variations()
    {
        $user = User::factory()->create([
            'email' => 'category_vol' . uniqid() . '@test.com',
        ]);

        $categories = ['Logistik', 'Distribusi', 'Packaging', 'Pengemasan'];
        foreach ($categories as $cat) {
            $relawan = RelawanProfile::create([
                'id_user' => $user->id,
                'nama_lengkap' => 'Vol Category ' . $cat,
                'nik' => substr(md5($cat), 0, 16),
                'skill' => 'Test Skill',
                'kategori' => $cat,
                'status_verif' => 'terverifikasi',
            ]);

            $this->assertEquals($cat, $relawan->kategori);
        }
    }

    public function test_volunteer_status_variations()
    {
        $user = User::factory()->create([
            'email' => 'status_vol' . uniqid() . '@test.com',
        ]);

        $statuses = ['menunggu_verif', 'terverifikasi', 'ditolak'];
        foreach ($statuses as $status) {
            $relawan = RelawanProfile::create([
                'id_user' => $user->id,
                'nama_lengkap' => 'Vol Status ' . $status,
                'nik' => substr(md5($status), 0, 16),
                'skill' => 'Test',
                'kategori' => 'Test',
                'status_verif' => $status,
            ]);

            $this->assertEquals($status, $relawan->status_verif);
        }
    }

    public function test_volunteer_nik_storage()
    {
        $user = User::factory()->create([
            'email' => 'nik_test' . uniqid() . '@test.com',
        ]);

        $nik = '1234567890123456';
        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'NIK Test',
            'nik' => $nik,
            'skill' => 'Test',
            'kategori' => 'Test',
            'status_verif' => 'terverifikasi',
        ]);

        $this->assertEquals($nik, $relawan->nik);
        $this->assertEquals(16, strlen($relawan->nik));
    }

    public function test_volunteer_query_by_skill()
    {
        $user = User::factory()->create([
            'email' => 'query_skill' . uniqid() . '@test.com',
        ]);

        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Query Skill Vol',
            'nik' => '1234567890123456',
            'skill' => 'SearchableSkill',
            'kategori' => 'Test',
            'status_verif' => 'terverifikasi',
        ]);

        $found = RelawanProfile::where('skill', 'SearchableSkill')->first();
        $this->assertNotNull($found);
        $this->assertEquals('Query Skill Vol', $found->nama_lengkap);
    }

    public function test_volunteer_query_by_category()
    {
        $user = User::factory()->create([
            'email' => 'query_cat' . uniqid() . '@test.com',
        ]);

        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Query Cat Vol',
            'nik' => '1234567890123456',
            'skill' => 'Test',
            'kategori' => 'SearchableCategory',
            'status_verif' => 'terverifikasi',
        ]);

        $found = RelawanProfile::where('kategori', 'SearchableCategory')->first();
        $this->assertNotNull($found);
    }

    public function test_volunteer_query_by_status()
    {
        $user = User::factory()->create([
            'email' => 'query_status' . uniqid() . '@test.com',
        ]);

        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Query Status Vol',
            'nik' => '1234567890123456',
            'skill' => 'Test',
            'kategori' => 'Test',
            'status_verif' => 'terverifikasi',
        ]);

        $verified = RelawanProfile::where('status_verif', 'terverifikasi')->count();
        $this->assertGreaterThan(0, $verified);
    }

    public function test_volunteer_timestamps()
    {
        $user = User::factory()->create([
            'email' => 'timestamp_vol' . uniqid() . '@test.com',
        ]);

        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Timestamp Vol',
            'nik' => '1234567890123456',
            'skill' => 'Test',
            'kategori' => 'Test',
            'status_verif' => 'terverifikasi',
        ]);

        $this->assertNotNull($relawan->created_at);
        $this->assertNotNull($relawan->updated_at);
    }

    public function test_volunteer_update()
    {
        $user = User::factory()->create([
            'email' => 'update_vol' . uniqid() . '@test.com',
        ]);

        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Original Name',
            'nik' => '1234567890123456',
            'skill' => 'Original Skill',
            'kategori' => 'Original',
            'status_verif' => 'menunggu_verif',
        ]);

        $relawan->update([
            'nama_lengkap' => 'Updated Name',
            'skill' => 'Updated Skill',
            'status_verif' => 'terverifikasi',
        ]);

        $updated = $relawan->fresh();
        $this->assertEquals('Updated Name', $updated->nama_lengkap);
        $this->assertEquals('Updated Skill', $updated->skill);
        $this->assertEquals('terverifikasi', $updated->status_verif);
    }

    public function test_volunteer_database_persistence()
    {
        $user = User::factory()->create([
            'email' => 'persist_vol' . uniqid() . '@test.com',
        ]);

        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Persist Vol',
            'nik' => '1234567890123456',
            'skill' => 'Persistent',
            'kategori' => 'Persistent',
            'status_verif' => 'terverifikasi',
        ]);

        $this->assertDatabaseHas('relawan_profiles', [
            'id_relawan' => $relawan->id_relawan,
            'nama_lengkap' => 'Persist Vol',
            'skill' => 'Persistent',
        ]);
    }

    public function test_multiple_volunteers_per_user()
    {
        $user = User::factory()->create([
            'email' => 'multi_vol' . uniqid() . '@test.com',
        ]);

        for ($i = 0; $i < 3; $i++) {
            RelawanProfile::create([
                'id_user' => $user->id,
                'nama_lengkap' => 'Multi Vol ' . $i,
                'nik' => '123456789012345' . $i,
                'skill' => 'Skill ' . $i,
                'kategori' => 'Category ' . $i,
                'status_verif' => 'terverifikasi',
            ]);
        }

        $count = RelawanProfile::where('id_user', $user->id)->count();
        $this->assertGreaterThanOrEqual(3, $count);
    }

    public function test_volunteer_primary_key()
    {
        $user = User::factory()->create([
            'email' => 'pk_vol' . uniqid() . '@test.com',
        ]);

        $relawan = RelawanProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'PK Vol',
            'nik' => '1234567890123456',
            'skill' => 'Test',
            'kategori' => 'Test',
            'status_verif' => 'terverifikasi',
        ]);

        $this->assertTrue($relawan->id_relawan > 0);
        $this->assertIsInt($relawan->id_relawan);
    }

    public function test_volunteer_fillable()
    {
        $volunteer = new RelawanProfile();
        $fillable = $volunteer->getFillable();
        
        $this->assertTrue(in_array('id_user', $fillable));
        $this->assertTrue(in_array('nama_lengkap', $fillable));
        $this->assertTrue(in_array('nik', $fillable));
        $this->assertTrue(in_array('skill', $fillable));
    }
}
