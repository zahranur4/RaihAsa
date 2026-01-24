<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\FoodRescue;
use App\Models\DonaturProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class FoodRescueTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test FoodRescue can be created
     */
    public function test_food_rescue_can_be_created()
    {
        $donatur = DonaturProfile::factory()->create();
        $foodRescue = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Nasi Kuning',
            'porsi' => 20,
            'waktu_dibuat' => Carbon::now(),
            'status' => 'available',
        ]);

        $this->assertNotNull($foodRescue->id_food);
        $this->assertEquals('Nasi Kuning', $foodRescue->nama_makanan);
    }

    /**
     * Test FoodRescue status values
     */
    public function test_food_rescue_status_values()
    {
        $donatur = DonaturProfile::factory()->create();
        $statuses = ['available', 'claimed', 'expired'];
        
        foreach ($statuses as $status) {
            $foodRescue = FoodRescue::create([
                'id_donatur' => $donatur->id_donatur,
                'nama_makanan' => 'Test Food',
                'porsi' => 10,
                'waktu_dibuat' => Carbon::now(),
                'status' => $status,
            ]);
            
            $this->assertEquals($status, $foodRescue->status);
        }
    }

    /**
     * Test FoodRescue can be updated
     */
    public function test_food_rescue_can_be_updated()
    {
        $donatur = DonaturProfile::factory()->create();
        $foodRescue = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Makanan Lama',
            'porsi' => 10,
            'waktu_dibuat' => Carbon::now(),
            'status' => 'available',
        ]);

        $foodRescue->update([
            'status' => 'claimed',
            'nama_makanan' => 'Makanan Baru',
        ]);

        $this->assertEquals('Makanan Baru', $foodRescue->nama_makanan);
        $this->assertEquals('claimed', $foodRescue->status);
    }

    /**
     * Test FoodRescue has fillable attributes
     */
    public function test_food_rescue_fillable_attributes()
    {
        $foodRescue = new FoodRescue();
        $expectedFillable = [
            'id_donatur',
            'nama_makanan',
            'porsi',
            'waktu_dibuat',
            'waktu_expired',
            'status',
            'id_claimer',
        ];

        foreach ($expectedFillable as $attr) {
            $this->assertContains($attr, $foodRescue->getFillable());
        }
    }

    /**
     * Test FoodRescue quantity
     */
    public function test_food_rescue_quantity_tracking()
    {
        $donatur = DonaturProfile::factory()->create();
        $foodRescue = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Makanan Test',
            'porsi' => 50,
            'waktu_dibuat' => Carbon::now(),
            'status' => 'available',
        ]);

        $this->assertEquals(50, $foodRescue->porsi);
    }

    /**
     * Test FoodRescue can be deleted
     */
    public function test_food_rescue_can_be_deleted()
    {
        $donatur = DonaturProfile::factory()->create();
        $foodRescue = FoodRescue::create([
            'id_donatur' => $donatur->id_donatur,
            'nama_makanan' => 'Makanan untuk dihapus',
            'porsi' => 10,
            'waktu_dibuat' => Carbon::now(),
            'status' => 'available',
        ]);

        $id = $foodRescue->id_food;
        $foodRescue->delete();

        $this->assertNull(FoodRescue::find($id));
    }
}
