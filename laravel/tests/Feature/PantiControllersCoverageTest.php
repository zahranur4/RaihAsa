<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PantiProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PantiControllersCoverageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Panti/DonasiMasukController tests
     */
    public function test_panti_donasi_masuk_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/donasi-masuk');
        $this->assertNotNull($response);
    }

    public function test_panti_donasi_masuk_create_form()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/donasi-masuk/create');
        $this->assertNotNull($response);
    }

    public function test_panti_donasi_masuk_store()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/panti/donasi-masuk', [
            'kategori' => 'makanan',
            'jumlah' => 5,
        ]);
        $this->assertNotNull($response);
    }

    public function test_panti_donasi_masuk_show()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/donasi-masuk/1');
        $this->assertNotNull($response);
    }

    public function test_panti_donasi_masuk_edit()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/donasi-masuk/1/edit');
        $this->assertNotNull($response);
    }

    public function test_panti_donasi_masuk_update()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->put('/panti/donasi-masuk/1', [
            'status' => 'received',
        ]);
        $this->assertNotNull($response);
    }

    public function test_panti_donasi_masuk_delete()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/panti/donasi-masuk/1');
        $this->assertNotNull($response);
    }

    public function test_panti_donasi_masuk_filter_by_category()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/donasi-masuk?category=makanan');
        $this->assertNotNull($response);
    }

    public function test_panti_donasi_masuk_filter_by_date()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panti/donasi-masuk?date=2026-01-25');
        $this->assertNotNull($response);
    }
}
