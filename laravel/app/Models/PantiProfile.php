<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PantiProfile extends Model
{
    use HasFactory;

    protected $table = 'panti_profiles';
    protected $primaryKey = 'id_panti';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'nama_panti',
        'alamat_lengkap',
        'kota',
        'latitude',
        'longitude',
        'no_sk',
        'status_verif',
    ];

    /**
     * Get the user that owns the panti profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
