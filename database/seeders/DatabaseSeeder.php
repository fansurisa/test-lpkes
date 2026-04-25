<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole  = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@lpkesolutions.com'],
            [
                'name'                 => 'Administrator',
                'password'             => bcrypt('password'),
                'user_type'            => 'nakes',
                'profession'           => 'dokter',
                'phone'                => '08123456789',
                'profile_completed_at' => now(),
                'email_verified_at'    => now(),
            ]
        );
        $admin->assignRole($adminRole);

        // Demo nakes user
        $nakes = User::firstOrCreate(
            ['email' => 'nakes@lpkesolutions.com'],
            [
                'name'                 => 'Dr. Siti Nakes',
                'password'             => bcrypt('password'),
                'user_type'            => 'nakes',
                'profession'           => 'dokter',
                'str_number'           => 'STR-12345-67890',
                'phone'                => '08111111111',
                'profile_completed_at' => now(),
                'email_verified_at'    => now(),
            ]
        );
        $nakes->assignRole($userRole);

        // Categories
        $categories = [
            ['name' => 'Keperawatan',     'slug' => 'keperawatan',    'color' => '#0ea5e9'],
            ['name' => 'Kedokteran',      'slug' => 'kedokteran',     'color' => '#8b5cf6'],
            ['name' => 'Farmasi',         'slug' => 'farmasi',        'color' => '#10b981'],
            ['name' => 'Kebidanan',       'slug' => 'kebidanan',      'color' => '#f59e0b'],
            ['name' => 'Gizi & Nutrisi',  'slug' => 'gizi-nutrisi',   'color' => '#ef4444'],
            ['name' => 'Rekam Medis',     'slug' => 'rekam-medis',    'color' => '#6366f1'],
            ['name' => 'K3 Rumah Sakit',  'slug' => 'k3-rumah-sakit', 'color' => '#ec4899'],
            ['name' => 'Radiologi',       'slug' => 'radiologi',      'color' => '#14b8a6'],
            ['name' => 'Fisioterapi',     'slug' => 'fisioterapi',    'color' => '#f97316'],
            ['name' => 'Manajemen RS',    'slug' => 'manajemen-rs',   'color' => '#64748b'],
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Sample trainings
        $samples = [
            [
                'title'       => 'Pelatihan Bantuan Hidup Dasar (BHD) untuk Tenaga Kesehatan',
                'type'        => 'event',
                'category'    => 'kedokteran',
                'price'       => 350000,
                'is_free'     => false,
                'skp_value'   => 3,
                'duration'    => '2 Hari',
                'description' => 'Pelatihan komprehensif tentang teknik bantuan hidup dasar yang harus dikuasai setiap tenaga kesehatan. Mencakup teknik CPR terbaru sesuai guideline AHA 2020, penanganan tersedak, dan penggunaan AED.',
                'objectives'  => "Setelah mengikuti pelatihan ini, peserta diharapkan mampu:\n- Memahami konsep dasar BHD\n- Melakukan CPR dengan teknik yang benar\n- Mengoperasikan AED\n- Mengenali tanda-tanda henti jantung",
                'thumbnail'   => 'https://images.unsplash.com/photo-1584516150909-c43483ee7932?w=800&h=600&fit=crop',
                'trainer_name'=> 'dr. Andi Wijaya, Sp.EM',
                'trainer_title'=> 'Dokter Spesialis Emergency Medicine',
                'pelataran_link' => 'https://plataran.kemkes.go.id/',
                'is_published'=> true,
            ],
            [
                'title'       => 'Manajemen Nyeri Pasca Operasi pada Pasien Dewasa',
                'type'        => 'ecourse',
                'category'    => 'keperawatan',
                'price'       => 0,
                'is_free'     => true,
                'skp_value'   => 2,
                'duration'    => '4 Jam',
                'description' => 'E-course online tentang prinsip-prinsip manajemen nyeri pada pasien pasca operasi, termasuk pengkajian nyeri, intervensi farmakologi dan non-farmakologi.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&h=600&fit=crop',
                'trainer_name'=> 'Ns. Sari Kusumawati, M.Kep',
                'trainer_title'=> 'Perawat Spesialis Bedah',
                'is_published'=> true,
            ],
            [
                'title'       => 'Update Penatalaksanaan Diabetes Mellitus Tipe 2',
                'type'        => 'event',
                'category'    => 'kedokteran',
                'price'       => 500000,
                'is_free'     => false,
                'skp_value'   => 5,
                'duration'    => '1 Hari',
                'description' => 'Workshop terbaru tentang penatalaksanaan DM Tipe 2 dengan pendekatan personalized medicine. Fokus pada strategi farmakologis terbaru dan target glikemik individual.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=800&h=600&fit=crop',
                'trainer_name'=> 'Prof. Dr. dr. Agung Pranoto, Sp.PD-KEMD',
                'trainer_title'=> 'Konsultan Endokrinologi',
                'is_published'=> true,
            ],
            [
                'title'       => 'Asuhan Kebidanan pada Ibu Hamil dengan Risiko Tinggi',
                'type'        => 'ecourse',
                'category'    => 'kebidanan',
                'price'       => 250000,
                'is_free'     => false,
                'skp_value'   => 4,
                'duration'    => '8 Jam',
                'description' => 'Pelatihan online untuk bidan dalam memberikan asuhan kebidanan pada ibu hamil dengan kondisi risiko tinggi seperti preeklampsia, diabetes gestasional, dan plasenta previa.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1518152006812-edab29b069ac?w=800&h=600&fit=crop',
                'trainer_name'=> 'Bd. Rina Wulandari, M.Keb',
                'trainer_title'=> 'Bidan Senior',
                'is_published'=> true,
            ],
            [
                'title'       => 'Pelayanan Kefarmasian di Rumah Sakit',
                'type'        => 'event',
                'category'    => 'farmasi',
                'price'       => 0,
                'is_free'     => true,
                'skp_value'   => 0,
                'duration'    => '3 Jam',
                'description' => 'Seminar gratis tentang pengembangan pelayanan kefarmasian di rumah sakit, termasuk pharmaceutical care dan medication safety.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1576602976047-174e57a47881?w=800&h=600&fit=crop',
                'trainer_name'=> 'apt. Bambang Setyawan, M.Farm',
                'trainer_title'=> 'Apoteker Klinis',
                'is_published'=> true,
            ],
            [
                'title'       => 'Gizi Klinis untuk Pasien Kritis di ICU',
                'type'        => 'ecourse',
                'category'    => 'gizi-nutrisi',
                'price'       => 200000,
                'is_free'     => false,
                'skp_value'   => 3,
                'duration'    => '6 Jam',
                'description' => 'E-course tentang assessment dan intervensi gizi pada pasien kritis di ICU. Mencakup enteral & parenteral nutrition.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800&h=600&fit=crop',
                'trainer_name'=> 'Dr. Maya Hapsari, MGizi',
                'trainer_title'=> 'Ahli Gizi Klinis',
                'is_published'=> true,
            ],
            [
                'title'       => 'K3 Rumah Sakit: Pencegahan Infeksi Nosokomial',
                'type'        => 'event',
                'category'    => 'k3-rumah-sakit',
                'price'       => 150000,
                'is_free'     => false,
                'skp_value'   => 2,
                'duration'    => '1 Hari',
                'description' => 'Workshop tentang prinsip dan praktik pencegahan infeksi nosokomial di rumah sakit. Membahas hand hygiene, APD, dan isolasi.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1583912267550-d6c2ac3196c0?w=800&h=600&fit=crop',
                'trainer_name'=> 'Dr. Hadi Santoso, MARS',
                'trainer_title'=> 'Komite PPI RS',
                'is_published'=> true,
            ],
            [
                'title'       => 'Manajemen Rumah Sakit Modern',
                'type'        => 'ecourse',
                'category'    => 'manajemen-rs',
                'price'       => 750000,
                'is_free'     => false,
                'skp_value'   => 0,
                'duration'    => '20 Jam',
                'description' => 'Program e-course komprehensif untuk manajer rumah sakit. Mencakup leadership, operasional, finansial, dan strategic planning.',
                'thumbnail'   => 'https://images.unsplash.com/photo-1527613426441-4da17471b66d?w=800&h=600&fit=crop',
                'trainer_name'=> 'Dr. Indra Permana, MBA, MARS',
                'trainer_title'=> 'Direktur RS',
                'is_published'=> true,
            ],
        ];

        foreach ($samples as $s) {
            $cat = Category::where('slug', $s['category'])->first();
            unset($s["category"]);
            Training::firstOrCreate(
                ['slug' => Str::slug($s['title'])],
                array_merge($s, [
                    'category_id' => $cat->id,
                    'slug'        => Str::slug($s['title']),
                ])
            );
        }

        $this->command->info('Database seeded successfully.');
        $this->command->info('Admin: admin@lpkesolutions.com / password');
        $this->command->info('Nakes: nakes@lpkesolutions.com / password');
    }
}
