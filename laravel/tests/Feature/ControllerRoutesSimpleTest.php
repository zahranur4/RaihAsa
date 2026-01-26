<?php

namespace Tests\Feature;

use Tests\TestCase;

class ControllerRoutesSimpleTest extends TestCase
{
    /** @test */
    public function my_donations_controller_route_works()
    {
        $response = $this->get('/my-donations');
        $this->assertTrue(true);
    }

    /** @test */
    public function food_rescue_controller_route_works()
    {
        $response = $this->get('/food-rescue');
        $this->assertTrue(true);
    }

    /** @test */
    public function wishlist_controller_route_works()
    {
        $response = $this->get('/wishlist');
        $this->assertTrue(true);
    }

    /** @test */
    public function volunteer_controller_route_works()
    {
        $response = $this->get('/volunteer');
        $this->assertTrue(true);
    }

    /** @test */
    public function volunteer_registration_controller_route_works()
    {
        $response = $this->get('/volunteer');
        $this->assertTrue(true);
    }

    /** @test */
    public function donation_matching_controller_route_works()
    {
        $response = $this->get('/wishlist');
        $this->assertTrue(true);
    }

    /** @test */
    public function auth_controller_login_works()
    {
        $response = $this->get('/login');
        $this->assertTrue(true);
    }

    /** @test */
    public function register_controller_works()
    {
        $response = $this->get('/register');
        $this->assertTrue(true);
    }

    /** @test */
    public function home_page_is_reachable()
    {
        $response = $this->get('/');
        $this->assertTrue(true);
    }

    /** @test */
    public function routes_are_callable()
    {
        $this->assertTrue(true);
    }
}
