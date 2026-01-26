<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederCoverageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test database seeder which calls all other seeders
     */
    public function test_database_seeder_runs()
    {
        $this->seed(DatabaseSeeder::class);
        $this->assertTrue(true);
    }

    public function test_database_seeder_multiple_calls()
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);
        $this->assertTrue(true);
    }
}
