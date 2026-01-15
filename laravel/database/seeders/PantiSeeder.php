<?php

namespace Database\Seeders;

use App\Models\PantiProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class PantiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create panti users and their profiles
        $pantiData = [
            [
                'name' => 'Panti Sukses Selalu',
                'email' => 'panti.sukses@example.com',
                'phone' => '081234567890',
                'address' => 'Jalan Harapan No. 45',
                'city' => 'Jakarta',
                'postal_code' => '12345',
                'description' => 'Panti asuhan yang berfokus pada pendidikan dan pemberdayaan anak-anak.',
                'panti_name' => 'Panti Sukses Selalu',
                'panti_address' => 'Jalan Harapan No. 45, Jakarta',
                'panti_city' => 'Jakarta',
                'status_verif' => 'verified', // Verified - can publish wishlists
            ],
            [
                'name' => 'Rumah Belajar Cemerlang',
                'email' => 'rumah.belajar@example.com',
                'phone' => '082345678901',
                'address' => 'Jalan Pendidikan No. 78',
                'city' => 'Tangerang',
                'postal_code' => '15143',
                'description' => 'Lembaga pendidikan yang menyediakan program belajar gratis untuk anak kurang mampu.',
                'panti_name' => 'Rumah Belajar Cemerlang',
                'panti_address' => 'Jalan Pendidikan No. 78, Tangerang',
                'panti_city' => 'Tangerang',
                'status_verif' => 'pending', // Pending - cannot publish wishlists
            ],
            [
                'name' => 'Panti Jompo Bahagia',
                'email' => 'panti.jompo@example.com',
                'phone' => '083456789012',
                'address' => 'Jalan Sejahtera No. 23',
                'city' => 'Depok',
                'postal_code' => '16424',
                'description' => 'Panti jompo yang memberikan perawatan dan kasih sayang kepada lansia.',
                'panti_name' => 'Panti Jompo Bahagia',
                'panti_address' => 'Jalan Sejahtera No. 23, Depok',
                'panti_city' => 'Depok',
                'status_verif' => 'verified', // Verified - can publish wishlists
            ],
            [
                'name' => 'Panti Asuhan Kasih',
                'email' => 'panti.kasih@example.com',
                'phone' => '084567890123',
                'address' => 'Jalan Kasih No. 56',
                'city' => 'Bekasi',
                'postal_code' => '17113',
                'description' => 'Panti asuhan yang peduli terhadap kesehatan dan keselamatan anak.',
                'panti_name' => 'Panti Asuhan Kasih',
                'panti_address' => 'Jalan Kasih No. 56, Bekasi',
                'panti_city' => 'Bekasi',
                'status_verif' => 'pending', // Pending - cannot publish wishlists
            ],
            [
                'name' => 'Panti Asuhan Ceria',
                'email' => 'panti.ceria@example.com',
                'phone' => '085678901234',
                'address' => 'Jalan Ceria No. 89',
                'city' => 'Bogor',
                'postal_code' => '16810',
                'description' => 'Panti asuhan dengan program pemberdayaan dan pelatihan keterampilan.',
                'panti_name' => 'Panti Asuhan Ceria',
                'panti_address' => 'Jalan Ceria No. 89, Bogor',
                'panti_city' => 'Bogor',
                'status_verif' => 'verified', // Verified - can publish wishlists
            ],
            [
                'name' => 'Yayasan Peduli Anak Negeri',
                'email' => 'yayasan.peduli@example.com',
                'phone' => '086789012345',
                'address' => 'Jalan Peduli No. 12',
                'city' => 'Bandung',
                'postal_code' => '40111',
                'description' => 'Yayasan yang fokus pada pendidikan dan kesehatan anak terlantar.',
                'panti_name' => 'Yayasan Peduli Anak Negeri',
                'panti_address' => 'Jalan Peduli No. 12, Bandung',
                'panti_city' => 'Bandung',
                'status_verif' => 'rejected', // Rejected - cannot publish wishlists
            ],
            [
                'name' => 'Panti Asuhan Mulia',
                'email' => 'panti.mulia@example.com',
                'phone' => '087890123456',
                'address' => 'Jalan Mulia No. 34',
                'city' => 'Semarang',
                'postal_code' => '50111',
                'description' => 'Panti asuhan yang baru berdiri dan sedang mengurus legalitas.',
                'panti_name' => 'Panti Asuhan Mulia',
                'panti_address' => 'Jalan Mulia No. 34, Semarang',
                'panti_city' => 'Semarang',
                'status_verif' => 'pending', // Pending - cannot publish wishlists
            ],
        ];

        foreach ($pantiData as $data) {
            // Create user for panti
            $user = User::create([
                'nama' => $data['name'],
                'email' => $data['email'],
                'kata_sandi' => bcrypt('password123'),
                'nomor_telepon' => $data['phone'],
                'alamat' => $data['address'],
                'kota' => $data['city'],
                'kode_pos' => $data['postal_code'],
                'deskripsi' => $data['description'],
                'email_verified_at' => now(),
            ]);

            // Create panti profile
            PantiProfile::create([
                'id_user' => $user->id,
                'nama_panti' => $data['panti_name'],
                'alamat_lengkap' => $data['panti_address'],
                'kota' => $data['panti_city'],
                'no_sk' => 'SK-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'status_verif' => $data['status_verif'],
            ]);
        }
    }
}
