<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';
    protected $primaryKey = 'id_wishlist';
    
    protected $fillable = [
        'id_panti',
        'nama_barang',
        'kategori',
        'jumlah',
        'urgensi',
        'status',
        'image',
    ];

    public function panti()
    {
        return $this->belongsTo(PantiProfile::class, 'id_panti', 'id_panti');
    }
}
