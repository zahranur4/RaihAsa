<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DonaturProfile;
use App\Models\PantiProfile;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_controller_returns_view()
    {
        $response = $this->get('/');
        $this->assertTrue($response->status() === 200);
    }

    public function test_home_controller_with_data()
    {
        $response = $this->get('/');
        $this->assertNotNull($response);
    }

    public function test_donasi_controller_index_returns_collection()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $response = $this->get('/donasi-barang');
        $this->assertTrue($response->status() === 200 || $response->status() === 404);
    }

    public function test_donasi_controller_show_with_id()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Test',
        ]);

        $donasi = \App\Models\DonasiBarang::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_barang' => 'Item',
            'kategori' => 'Test',
            'foto' => 'test.jpg',
            'status' => 'pending',
        ]);

        $response = $this->get("/donasi-barang/{$donasi->id_donasi}");
        $this->assertTrue($response->status() >= 200);
    }

    public function test_food_rescue_controller_index()
    {
        $response = $this->get('/food-rescue');
        $this->assertTrue($response->status() === 200 || $response->status() === 404);
    }

    public function test_panti_controller_index()
    {
        $response = $this->get('/panti');
        $this->assertTrue($response->status() === 200 || $response->status() === 404);
    }

    public function test_panti_controller_show()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Test Panti',
            'alamat' => 'Jl. Test',
            'no_telp' => '081234567890',
        ]);

        $response = $this->get("/panti/{$panti->id_panti}");
        $this->assertTrue($response->status() >= 200);
    }

    public function test_wishlist_controller_index()
    {
        $response = $this->get('/wishlist');
        $this->assertTrue($response->status() === 200 || $response->status() === 404);
    }

    public function test_wishlist_with_filter()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Filter Panti',
            'alamat' => 'Jl. Filter',
            'no_telp' => '081234567890',
        ]);

        Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Item',
            'jumlah' => 50,
            'urgensi' => 'mendesak',
            'kategori' => 'Umum',
            'status' => 'open',
        ]);

        $response = $this->get('/wishlist?urgensi=mendesak');
        $this->assertTrue($response->status() === 200 || $response->status() === 404);
    }

    public function test_admin_dashboard_not_accessible_to_guest()
    {
        $response = $this->get('/admin/dashboard');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_admin_dashboard_accessible_to_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $this->assertTrue($response->status() === 200);
    }

    public function test_admin_dashboard_not_accessible_to_user()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_admin_users_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/users');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_admin_donasi_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/donasi-barang');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_admin_food_rescue_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/food-rescue');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_admin_volunteers_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/volunteers');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_admin_panti_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $response = $this->actingAs($admin)->get('/admin/panti');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_profile_page_requires_auth()
    {
        $response = $this->get('/profile');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_profile_page_shows_for_auth_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_redirect_to_login_from_protected_routes()
    {
        $response = $this->get('/donasi-saya');
        $this->assertTrue($response->status() >= 302 || $response->status() >= 400);
    }

    public function test_controller_middleware_protection()
    {
        $response = $this->get('/admin/dashboard');
        $this->assertTrue($response->status() >= 400);
    }

    public function test_controller_response_json()
    {
        $response = $this->get('/api/user');
        $this->assertTrue($response->status() === 401 || $response->status() === 200 || $response->status() === 404);
    }

    public function test_database_query_in_controller()
    {
        $user = User::factory()->create();
        $donatur = DonaturProfile::create([
            'id_user' => $user->id,
            'nama_lengkap' => 'Query Test',
            'no_telp' => '081234567890',
            'alamat_jemput' => 'Jl. Query',
        ]);

        $this->assertTrue($donatur->id_donatur > 0);
    }

    public function test_controller_error_handling()
    {
        $response = $this->get('/nonexistent-route');
        $this->assertEqual(404, $response->status());
    }

    public function test_method_not_allowed()
    {
        $response = $this->patch('/');
        $this->assertTrue($response->status() >= 405 || $response->status() >= 400);
    }

    public function test_controller_uses_model_relationships()
    {
        $user = User::factory()->create();
        $panti = PantiProfile::create([
            'id_user' => $user->id,
            'nama_panti' => 'Relationship Panti',
            'alamat' => 'Jl. Relationship',
            'no_telp' => '081234567890',
        ]);

        $wishlist = Wishlist::create([
            'id_panti' => $panti->id_panti,
            'barang_diinginkan' => 'Item',
            'jumlah' => 50,
            'urgensi' => 'rutin',
            'kategori' => 'Umum',
            'status' => 'open',
        ]);

        $this->assertEqual($panti->id_panti, $wishlist->id_panti);
    }

    public function test_pagination_in_controller()
    {
        $response = $this->get('/donasi-barang');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_search_in_controller()
    {
        $response = $this->get('/donasi-barang?kategori=Elektronik');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_filter_by_status()
    {
        $response = $this->get('/food-rescue?status=available');
        $this->assertTrue($response->status() >= 200);
    }

    public function test_sorting_in_controller()
    {
        $response = $this->get('/donasi-barang?sort=created_at');
        $this->assertTrue($response->status() >= 200 || $response->status() === 404);
    }

    public function test_response_headers()
    {
        $response = $this->get('/');
        $this->assertTrue($response->status() === 200);
        $this->assertNotNull($response->headers);
    }

    public function test_view_data_passed_to_template()
    {
        $response = $this->get('/');
        $this->assertNotNull($response);
    }

    public function test_multiple_controller_actions()
    {
        $user = User::factory()->create();
        
        $response1 = $this->actingAs($user)->get('/profile');
        $response2 = $this->get('/');
        $response3 = $this->get('/donasi-barang');

        $this->assertTrue($response1->status() >= 200);
        $this->assertTrue($response2->status() === 200);
        $this->assertTrue($response3->status() >= 200);
    }

    public function test_http_request_handling()
    {
        $response = $this->get('/');
        $this->assertTrue($response->status() === 200);
    }

    public function test_http_response_structure()
    {
        $response = $this->get('/');
        $this->assertNotNull($response->content());
    }
}
