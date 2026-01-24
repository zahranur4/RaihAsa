<?php

namespace Tests\Unit;

use Tests\TestCase;

class AppVerificationTest extends TestCase
{
    /** @test */
    public function app_namespace_exists()
    {
        $this->assertTrue(class_exists('App\Models\User'));
    }

    /** @test */
    public function wishlist_model_exists()
    {
        $this->assertTrue(class_exists('App\Models\Wishlist'));
    }

    /** @test */
    public function panti_profile_model_exists()
    {
        $this->assertTrue(class_exists('App\Models\PantiProfile'));
    }

    /** @test */
    public function relawan_profile_model_exists()
    {
        $this->assertTrue(class_exists('App\Models\RelawanProfile'));
    }

    /** @test */
    public function food_rescue_model_exists()
    {
        $this->assertTrue(class_exists('App\Models\FoodRescue'));
    }

    /** @test */
    public function donasi_barang_model_exists()
    {
        $this->assertTrue(class_exists('App\Models\DonasiBarang'));
    }

    /** @test */
    public function donatur_profile_model_exists()
    {
        $this->assertTrue(class_exists('App\Models\DonaturProfile'));
    }

    /** @test */
    public function controllers_namespace_exists()
    {
        $this->assertTrue(class_exists('App\Http\Controllers\WishlistController'));
    }

    /** @test */
    public function food_rescue_controller_exists()
    {
        $this->assertTrue(class_exists('App\Http\Controllers\FoodRescueController'));
    }

    /** @test */
    public function volunteer_controller_exists()
    {
        $this->assertTrue(class_exists('App\Http\Controllers\VolunteerController'));
    }
}
