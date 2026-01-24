<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonaturProfile extends Model
{
    use HasFactory;

    protected $table = 'donatur_profiles';
    protected $primaryKey = 'id_donatur';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'no_telp',
        'alamat_jemput',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function donations()
    {
        return $this->hasMany(DonasiBarang::class, 'id_donatur');
    }

    public function foodRescues()
    {
        return $this->hasMany(FoodRescue::class, 'id_donatur');
    }
}
