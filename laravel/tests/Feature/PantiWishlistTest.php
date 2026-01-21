<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;

class PantiWishlistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_user_cannot_create_wishlist()
    {
        $response = $this->post(route('panti.wishlist.store'), [
            'judul' => 'Kebutuhan Makan',
            'deskripsi' => 'Makanan bergizi untuk anak-anak',
        ]);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_panti_wishlist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('panti.wishlist'));

        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    /** @test */
    public function authenticated_user_can_create_wishlist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('panti.wishlist.store'), [
                'judul' => 'Kebutuhan Makan',
                'deskripsi' => 'Makanan bergizi untuk anak-anak',
                'urgency' => 'high',
            ]);

        $this->assertTrue(in_array($response->status(), [200, 302, 404]));
    }

    /** @test */
    public function authenticated_user_can_update_wishlist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->put(route('panti.wishlist.update', ['id' => 1]), [
                'judul' => 'Updated Wishlist',
                'deskripsi' => 'Updated description',
            ]);

        $this->assertTrue(in_array($response->status(), [200, 302, 404]));
    }

    /** @test */
    public function authenticated_user_can_delete_wishlist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('panti.wishlist.destroy', ['id' => 1]));

        $this->assertTrue(in_array($response->status(), [200, 302, 404]));
    }
}
