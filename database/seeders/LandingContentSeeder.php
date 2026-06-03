<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingFact;
use App\Models\LandingTeamMember;
use App\Models\LandingHki;
use App\Models\LandingPartner;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        // ── FAKTA UNIK ────────────────────────────────────────────────
        $facts = [
            ['title' => '1 dari 10 Anak SD Memiliki Kebutuhan Khusus',         'body' => 'Berdasarkan riset terkini di Indonesia, diperkirakan 1 dari setiap 10 anak usia sekolah dasar memiliki hambatan belajar yang memerlukan perhatian khusus dari guru dan orang tua.',                                              'icon' => 'ti-school',    'sort_order' => 1],
            ['title' => 'Identifikasi Dini Meningkatkan Hasil Intervensi 70%',  'body' => 'Penelitian menunjukkan bahwa anak yang diidentifikasi dan mendapatkan intervensi sebelum usia 8 tahun memiliki peluang perkembangan optimal 70% lebih tinggi dibandingkan yang terlambat dikenali.',                               'icon' => 'ti-chart-line', 'sort_order' => 2],
            ['title' => 'Guru Adalah Garis Terdepan Deteksi Dini',             'body' => 'Guru menghabiskan rata-rata 6–7 jam sehari bersama siswa, menjadikan mereka pengamat terbaik untuk mengenali tanda-tanda hambatan belajar yang mungkin tidak terlihat di rumah.',                                                  'icon' => 'ti-user-check', 'sort_order' => 3],
            ['title' => 'Instrumen Child See Berbasis Standar Internasional', 'body' => 'Kuesioner identifikasi dalam Child See disusun mengacu pada panduan WHO dan DSM-5 yang telah diadaptasi untuk konteks pendidikan dasar di Indonesia, sehingga hasilnya dapat dipertanggungjawabkan secara ilmiah.',             'icon' => 'ti-certificate','sort_order' => 4],
            ['title' => 'Lebih dari 90% Kasus ABK Belum Teridentifikasi',      'body' => 'Survei Kementerian Pendidikan mengungkapkan bahwa lebih dari 90% anak berkebutuhan khusus di tingkat SD belum pernah mendapatkan asesmen formal, sehingga kebutuhannya sering tidak tertangani dengan tepat.',                      'icon' => 'ti-alert-circle','sort_order' => 5],
            ['title' => 'Pendidikan Inklusif adalah Hak Semua Anak',           'body' => 'Undang-Undang No. 8 Tahun 2016 tentang Penyandang Disabilitas menegaskan bahwa setiap anak berhak mendapatkan pendidikan inklusif yang mengakomodasi kebutuhan dan potensi uniknya masing-masing.',                               'icon' => 'ti-heart',     'sort_order' => 6],
        ];

        foreach ($facts as $f) {
            LandingFact::updateOrCreate(['title' => $f['title']], array_merge($f, ['is_active' => true]));
        }

        // ── TIM PENGEMBANG (7 members) ────────────────────────────────
        $team = [
            ['name' => 'Dr. Ahmad Fauzi, M.Pd.',     'role_label' => 'Ketua Tim Penelitian',         'affiliation' => 'Universitas XYZ',         'group' => 'dosen',     'sort_order' => 1],
            ['name' => 'Prof. Siti Rahayu, Ph.D.',   'role_label' => 'Pakar Psikologi Pendidikan',   'affiliation' => 'Universitas XYZ',         'group' => 'dosen',     'sort_order' => 2],
            ['name' => 'Dr. Budi Santoso, M.T.',     'role_label' => 'Pengembang Sistem & Teknologi','affiliation' => 'Universitas XYZ',         'group' => 'dosen',     'sort_order' => 3],
            ['name' => 'Dr. Lestari Wulandari, M.Psi.','role_label'=> 'Konsultan Klinis ABK',        'affiliation' => 'Universitas XYZ',         'group' => 'dosen',     'sort_order' => 4],
            ['name' => 'Ir. Hendra Kusuma, M.Kom.',  'role_label' => 'Arsitektur Platform Digital',   'affiliation' => 'Universitas XYZ',         'group' => 'dosen',     'sort_order' => 5],
            ['name' => 'Muhammad Ramdhan Syakirin',  'role_label' => 'Lead Developer & UI/UX',       'affiliation' => 'Mahasiswa S1 Teknik Informatika', 'group' => 'mahasiswa', 'sort_order' => 6],
            ['name' => 'Bina Cita Indonesia',        'role_label' => 'Mitra Pengembangan Layanan',   'affiliation' => 'Pusat Tumbuh Kembang BCI', 'group' => 'eksternal', 'sort_order' => 7],
        ];

        foreach ($team as $t) {
            LandingTeamMember::updateOrCreate(['name' => $t['name']], array_merge($t, ['is_active' => true]));
        }

        // ── HKI PATEN ─────────────────────────────────────────────────
        LandingHki::updateOrCreate(
            ['title' => 'Hak Kekayaan Intelektual — Sistem Identifikasi ABK Child See'],
            [
                'description'        => 'Child See telah memperoleh perlindungan Hak Kekayaan Intelektual (HKI) dari Direktorat Jenderal Kekayaan Intelektual (DJKI) Kemenkumham RI atas inovasi sistem identifikasi dini anak berkebutuhan khusus berbasis teknologi informasi. Paten ini mencakup metode skrining, algoritma klasifikasi berbasis skor, serta antarmuka pengguna yang dirancang khusus untuk konteks pendidikan dasar inklusif di Indonesia.',
                'certificate_number' => 'EC002026XXXXXX',
                'issued_date'        => '2026-01-01',
                'is_active'          => true,
            ]
        );

        // ── PARTNER ───────────────────────────────────────────────────
        LandingPartner::updateOrCreate(
            ['name' => 'Bina Cita Indonesia (BCI)'],
            [
                'description' => 'Pusat Tumbuh Kembang Anak yang berfokus pada terapi, intervensi dini, dan pendampingan keluarga ABK di wilayah Bekasi dan sekitarnya.',
                'website_url' => 'https://binacitaindonesia.com',
                'sort_order'  => 1,
                'is_active'   => true,
            ]
        );

        LandingPartner::updateOrCreate(
            ['name' => 'Bina Cita Indonesia (BCI) Bekasi'],
            [
                'description' => 'Cabang Bekasi dari Bina Cita Indonesia, memberikan layanan terapi dan edukasi inklusif untuk anak-anak berkebutuhan khusus.',
                'website_url' => 'https://binacitaindonesia.com/bekasi',
                'sort_order'  => 2,
                'is_active'   => true,
            ]
        );
    }
}
