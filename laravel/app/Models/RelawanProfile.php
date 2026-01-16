<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelawanProfile extends Model
{
    use HasFactory;

    protected $table = 'relawan_profiles';
    protected $primaryKey = 'id_relawan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'nik',
        'skill',
        'status_verif',
    ];

    /**
     * Get the user that owns the volunteer profile
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
