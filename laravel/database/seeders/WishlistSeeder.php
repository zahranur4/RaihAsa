<?php

namespace Database\Seeders;

use App\Models\PantiProfile;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pantiProfiles = PantiProfile::all();

        $wishlistData = [
            // Panti Sukses Selalu
            [
                'nama_barang' => 'Susu Formula Bayi',
                'kategori' => 'Makanan',
                'jumlah' => 20,
                'urgensi' => 'mendesak',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Beras Premium 10kg',
                'kategori' => 'Makanan',
                'jumlah' => 50,
                'urgensi' => 'rutin',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Buku Tulis dan Alat Tulis',
                'kategori' => 'Pendidikan',
                'jumlah' => 100,
                'urgensi' => 'pendidikan',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Obat-obatan Dasar',
                'kategori' => 'Kesehatan',
                'jumlah' => 30,
                'urgensi' => 'kesehatan',
                'status' => 'open',
            ],
            // Rumah Belajar Cemerlang
            [
                'nama_barang' => 'Laptop untuk Ruang Belajar',
                'kategori' => 'Pendidikan',
                'jumlah' => 5,
                'urgensi' => 'pendidikan',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Meja dan Kursi Belajar',
                'kategori' => 'Pendidikan',
                'jumlah' => 20,
                'urgensi' => 'pendidikan',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Makanan Bergizi untuk Murid',
                'kategori' => 'Makanan',
                'jumlah' => 100,
                'urgensi' => 'rutin',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Sepatu Sekolah',
                'kategori' => 'Pakaian',
                'jumlah' => 50,
                'urgensi' => 'pendidikan',
                'status' => 'open',
            ],
            // Panti Jompo Bahagia
            [
                'nama_barang' => 'Obat-obatan Lansia',
                'kategori' => 'Kesehatan',
                'jumlah' => 50,
                'urgensi' => 'kesehatan',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Makanan Lunak untuk Lansia',
                'kategori' => 'Makanan',
                'jumlah' => 100,
                'urgensi' => 'rutin',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Popok Dewasa',
                'kategori' => 'Kesehatan',
                'jumlah' => 200,
                'urgensi' => 'rutin',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Vitamin dan Suplemen',
                'kategori' => 'Kesehatan',
                'jumlah' => 50,
                'urgensi' => 'kesehatan',
                'status' => 'open',
            ],
            // Panti Asuhan Kasih
            [
                'nama_barang' => 'Popok Bayi',
                'kategori' => 'Kesehatan',
                'jumlah' => 500,
                'urgensi' => 'rutin',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Makanan Bayi dan Bubur',
                'kategori' => 'Makanan',
                'jumlah' => 50,
                'urgensi' => 'mendesak',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Pakaian Bayi',
                'kategori' => 'Pakaian',
                'jumlah' => 50,
                'urgensi' => 'rutin',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Alat Kesehatan Bayi',
                'kategori' => 'Kesehatan',
                'jumlah' => 20,
                'urgensi' => 'kesehatan',
                'status' => 'open',
            ],
            // Panti Asuhan Ceria
            [
                'nama_barang' => 'Peralatan Keterampilan',
                'kategori' => 'Pendidikan',
                'jumlah' => 30,
                'urgensi' => 'pendidikan',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Pakaian Layak Pakai',
                'kategori' => 'Pakaian',
                'jumlah' => 100,
                'urgensi' => 'rutin',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Nasi dan Lauk Pauk',
                'kategori' => 'Makanan',
                'jumlah' => 200,
                'urgensi' => 'rutin',
                'status' => 'open',
            ],
            [
                'nama_barang' => 'Obat dan Vitamin',
                'kategori' => 'Kesehatan',
                'jumlah' => 40,
                'urgensi' => 'kesehatan',
                'status' => 'open',
            ],
        ];

        $wishlistIndex = 0;
        foreach ($pantiProfiles as $index => $panti) {
            // Assign 4 wishlists per panti
            for ($i = 0; $i < 4; $i++) {
                if ($wishlistIndex < count($wishlistData)) {
                    $data = $wishlistData[$wishlistIndex];
                    Wishlist::create([
                        'id_panti' => $panti->id_panti,
                        'nama_barang' => $data['nama_barang'],
                        'kategori' => $data['kategori'],
                        'jumlah' => $data['jumlah'],
                        'urgensi' => $data['urgensi'],
                        'status' => $data['status'],
                        'image' => null,
                    ]);
                    $wishlistIndex++;
                }
            }
        }
    }
}
