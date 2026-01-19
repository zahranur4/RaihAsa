<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // Kolom sesuai migrasi (menggunakan Bahasa Indonesia)
    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'alamat',
        'nomor_telepon',
        'is_admin',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    // Alias: kalau ada kode yang masih mengandalkan atribut 'name', sediakan accessor
    public function getNameAttribute()
    {
        return $this->attributes['nama'] ?? null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    /**
     * Override password field used by the Authenticatable implementation
     * so Laravel uses `kata_sandi` column as the password.
     */
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    /**
     * Relationship to relawan_profiles
     */
    public function relawan_profiles()
    {
        return $this->hasMany(RelawanProfile::class, 'id_user', 'id');
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
