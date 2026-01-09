<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRescue extends Model
{
    use HasFactory;

    protected $table = 'food_rescue';
    protected $primaryKey = 'id_food';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_donatur',
        'nama_makanan',
        'porsi',
        'waktu_dibuat',
        'waktu_expired',
        'status',
        'id_claimer',
    ];

    protected $dates = ['waktu_dibuat','waktu_expired'];
}
