<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VolunteerActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample volunteer activities
        $activities = [
            [
                'title' => 'Mengajar Matematika',
                'description' => 'Mengajar matematika dasar untuk anak-anak di panti asuhan',
                'location' => 'Panti Asuhan Harapan, Coblong',
                'activity_date' => now()->addDays(7)->setHour(9)->setMinute(0),
                'category' => 'Edukasi & Literasi',
                'needed_volunteers' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'Workshop Melukis Anak',
                'description' => 'Workshop seni melukis untuk mengembangkan kreativitas anak',
                'location' => 'Rumah Yatim Piatu, Sukasari',
                'activity_date' => now()->addDays(10)->setHour(14)->setMinute(0),
                'category' => 'Kreatif & Psikososial',
                'needed_volunteers' => 1,
                'status' => 'active',
            ],
            [
                'title' => 'Pemeriksaan Kesehatan',
                'description' => 'Program pemeriksaan kesehatan rutin untuk anak-anak panti',
                'location' => 'Panti Asuhan Bersama, Bandung Utara',
                'activity_date' => now()->addDays(14)->setHour(10)->setMinute(0),
                'category' => 'Kesehatan & Gizi',
                'needed_volunteers' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Bantuan Banjir Bandung Timur',
                'description' => 'Kegiatan tanggap darurat untuk korban banjir',
                'location' => 'Ujungberung, Bandung Timur',
                'activity_date' => now()->subDays(5),
                'category' => 'Kemanusiaan & Kebencanaan',
                'needed_volunteers' => 10,
                'status' => 'completed',
            ],
            [
                'title' => 'Mengajar di Panti Asuhan',
                'description' => 'Program mengajar membaca dan menulis untuk anak-anak',
                'location' => 'Coblong, Bandung',
                'activity_date' => now()->subDays(3)->setHour(9)->setMinute(0),
                'category' => 'Edukasi & Literasi',
                'needed_volunteers' => 2,
                'status' => 'completed',
            ],
        ];

        foreach ($activities as $activity) {
            DB::table('volunteer_activities')->insert(array_merge($activity, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Sample registrations for Rina Wijaya (id = 5, assuming from seeder)
        DB::table('activity_volunteer')->insert([
            [
                'id_activity' => 1,
                'id_user' => 5,
                'status' => 'approved',
                'motivation' => 'Saya ingin membantu anak-anak belajar matematika',
                'emergency_contact_name' => 'Ibu Wijaya',
                'emergency_contact_phone' => '082212345678',
                'transportation' => 'motor',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
            [
                'id_activity' => 5,
                'id_user' => 5,
                'status' => 'completed',
                'motivation' => 'Untuk berbagi pengetahuan dengan anak-anak',
                'emergency_contact_name' => 'Ibu Wijaya',
                'emergency_contact_phone' => '082212345678',
                'transportation' => 'motor',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(3),
            ],
        ]);
    }
}
