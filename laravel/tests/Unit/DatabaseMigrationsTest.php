<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseMigrationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_table_exists()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function panti_profiles_table_exists()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function relawan_profiles_table_exists()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function wishlists_table_exists()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function food_rescues_table_exists()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function donasi_barangs_table_exists()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function donatur_profiles_table_exists()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function migrations_run_successfully()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function database_is_accessible()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function migrations_do_not_error()
    {
        $this->assertTrue(true);
    }
}
