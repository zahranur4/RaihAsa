<?php

namespace Tests\Feature;

use Tests\TestCase;

class HalamanDepanTest extends TestCase
{
    /** @test */
    public function halaman_beranda_bisa_dibuka()
    {
        $response = $this->get('/'); // Akses URL '/'
        $response->assertStatus(200); // Pastikan sukses (kode 200)
    }

    /** @test */
    public function halaman_login_bisa_dibuka()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}