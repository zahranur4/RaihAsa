<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\DonaturProfile;
use App\Models\DonasiBarang;
use App\Models\FoodRescue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DonaturProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $profile = DonaturProfile::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $profile->user);
        $this->assertEquals($user->id, $profile->user->id);
    }

    /** @test */
    public function it_has_many_donations()
    {
        $profile = DonaturProfile::factory()->create();
        $donation = DonasiBarang::factory()->create(['donatur_profile_id' => $profile->id]);

        $this->assertTrue($profile->donations->contains($donation));
    }

    /** @test */
    public function it_has_many_food_rescues()
    {
        $profile = DonaturProfile::factory()->create();
        $foodRescue = FoodRescue::factory()->create(['donatur_profile_id' => $profile->id]);

        $this->assertTrue($profile->foodRescues->contains($foodRescue));
    }
}
