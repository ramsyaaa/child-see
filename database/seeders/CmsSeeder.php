<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsPage;
use App\Models\CmsSection;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [

            // ──────────────────────────────────────────────────────────────
            // HOME
            // ──────────────────────────────────────────────────────────────
            [
                'slug'            => 'home',
                'title'           => 'Beranda',
                'seo_title'       => 'InkluSyncID — Identifikasi Anak Berkebutuhan Khusus',
                'seo_description' => 'InkluSyncID membantu orang tua dan guru mengidentifikasi potensi kebutuhan khusus anak secara dini melalui tes berbasis bukti ilmiah.',
                'seo_keywords'    => 'identifikasi ABK, anak berkebutuhan khusus, deteksi dini, inklusif, sekolah dasar',
                'sections' => [
                    ['section_key' => 'hero', 'label' => 'Hero Utama', 'sort_order' => 10, 'content' => [
                        'eyebrow'           => 'Deteksi Dini, Tumbuh Optimal',
                        'heading'           => 'Kenali Kebutuhan Anak Lebih Awal',
                        'body'              => 'InkluSyncID membantu orang tua dan guru mengenali tanda-tanda kebutuhan khusus anak melalui tes terstruktur yang mudah dipahami.',
                        'cta_primary_label' => 'Mulai Identifikasi',
                        'cta_secondary_label' => 'Pelajari Lebih Lanjut',
                    ]],
                    ['section_key' => 'about_snapshot', 'label' => 'Tentang Kami (Snapshot)', 'sort_order' => 20, 'content' => [
                        'eyebrow'    => 'Tentang InkluSyncID',
                        'heading'    => 'Kami Ada untuk Mendukung Tumbuh Kembang Anak',
                        'body_1'     => 'InkluSyncID adalah platform identifikasi anak berkebutuhan khusus berbasis web yang dirancang untuk membantu orang tua, guru, dan tenaga pendidik mengenali potensi hambatan perkembangan anak secara dini.',
                        'body_2'     => 'Kami menyediakan instrumen skrining terstandar untuk hambatan penglihatan, pendengaran, dan intelektual pada anak usia sekolah dasar.',
                        'cta_label'  => 'Lihat Cara Kerja',
                    ]],
                    ['section_key' => 'how_it_works', 'label' => 'Cara Kerja', 'sort_order' => 30, 'content' => [
                        'heading'       => 'Cara Kerja InkluSyncID',
                        'step_1_title'  => 'Daftar Akun',
                        'step_1_body'   => 'Buat akun gratis sebagai orang tua atau guru untuk mulai menggunakan layanan identifikasi.',
                        'step_2_title'  => 'Isi Kuesioner',
                        'step_2_body'   => 'Jawab pertanyaan seputar perilaku dan kemampuan anak berdasarkan pengamatan sehari-hari.',
                        'step_3_title'  => 'Dapatkan Hasil',
                        'step_3_body'   => 'Terima laporan hasil identifikasi lengkap dengan tingkat indikasi dan rekomendasi tindak lanjut.',
                    ]],
                    ['section_key' => 'cta_banner', 'label' => 'Banner CTA', 'sort_order' => 40, 'content' => [
                        'heading'  => 'Mulai Identifikasi Sekarang — Gratis',
                        'body'     => 'Identifikasi dini adalah langkah pertama menuju intervensi yang tepat dan masa depan yang lebih cerah bagi anak.',
                        'cta_1'   => 'Daftar Gratis',
                        'cta_2'   => 'Pelajari Jenis Identifikasi',
                    ]],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // ABOUT
            // ──────────────────────────────────────────────────────────────
            [
                'slug'            => 'about',
                'title'           => 'Tentang Kami',
                'seo_title'       => 'Tentang InkluSyncID | Misi & Visi',
                'seo_description' => 'Kenali InkluSyncID — platform identifikasi ABK yang dibangun untuk mendukung pendidikan inklusif di Indonesia.',
                'seo_keywords'    => 'tentang inklusyncid, platform ABK, pendidikan inklusif indonesia',
                'sections' => [
                    ['section_key' => 'hero', 'label' => 'Hero', 'sort_order' => 10, 'content' => [
                        'eyebrow' => 'Tentang Kami',
                        'heading' => 'Menuju Pendidikan Inklusif yang Lebih Baik',
                        'body'    => 'InkluSyncID lahir dari kepedulian terhadap anak-anak berkebutuhan khusus yang sering kali tidak teridentifikasi sejak dini.',
                    ]],
                    ['section_key' => 'mission', 'label' => 'Misi & Visi', 'sort_order' => 20, 'content' => [
                        'vision_heading' => 'Visi',
                        'vision_body'    => 'Menjadi platform identifikasi anak berkebutuhan khusus terpercaya yang mendukung pendidikan inklusif di seluruh Indonesia.',
                        'mission_heading'=> 'Misi',
                        'mission_body'   => 'Menyediakan instrumen skrining berbasis bukti yang mudah diakses oleh orang tua, guru, dan tenaga pendidik untuk mendeteksi hambatan perkembangan anak secara dini.',
                    ]],
                    ['section_key' => 'values', 'label' => 'Nilai-Nilai Kami', 'sort_order' => 30, 'content' => [
                        'heading'        => 'Nilai yang Kami Pegang',
                        'value_1_title'  => 'Inklusivitas',
                        'value_1_body'   => 'Setiap anak berhak mendapat perhatian dan dukungan yang sesuai dengan kebutuhannya.',
                        'value_2_title'  => 'Berbasis Bukti',
                        'value_2_body'   => 'Instrumen kami disusun berdasarkan penelitian dan standar klinis yang dapat dipertanggungjawabkan.',
                        'value_3_title'  => 'Aksesibilitas',
                        'value_3_body'   => 'Kami memastikan layanan dapat digunakan oleh siapa saja, kapan saja, dan di mana saja.',
                        'value_4_title'  => 'Kolaborasi',
                        'value_4_body'   => 'Kami percaya pada sinergi antara orang tua, guru, dan tenaga ahli untuk hasil terbaik bagi anak.',
                    ]],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // IDENTIFIKASI (types of screening)
            // ──────────────────────────────────────────────────────────────
            [
                'slug'            => 'identifikasi',
                'title'           => 'Jenis Identifikasi',
                'seo_title'       => 'Jenis Identifikasi ABK | InkluSyncID',
                'seo_description' => 'Pelajari tiga jenis identifikasi anak berkebutuhan khusus: hambatan penglihatan, pendengaran, dan intelektual.',
                'seo_keywords'    => 'identifikasi penglihatan, identifikasi pendengaran, identifikasi intelektual, ABK',
                'sections' => [
                    ['section_key' => 'hero', 'label' => 'Hero', 'sort_order' => 10, 'content' => [
                        'eyebrow'    => 'Instrumen Skrining',
                        'heading'    => 'Tiga Jenis Identifikasi',
                        'subheading' => 'Pilih jenis identifikasi sesuai dengan yang ingin Anda ketahui tentang anak.',
                    ]],
                    ['section_key' => 'penglihatan', 'label' => 'Hambatan Penglihatan', 'sort_order' => 20, 'content' => [
                        'heading'    => 'Hambatan Penglihatan',
                        'body'       => 'Skrining untuk mendeteksi gangguan atau hambatan visual yang mungkin mempengaruhi kemampuan belajar anak di sekolah.',
                        'question_count' => '12 pertanyaan',
                        'duration'   => '5–10 menit',
                        'color'      => '#1E3A5F',
                    ]],
                    ['section_key' => 'pendengaran', 'label' => 'Hambatan Pendengaran', 'sort_order' => 30, 'content' => [
                        'heading'    => 'Hambatan Pendengaran',
                        'body'       => 'Skrining untuk mendeteksi gangguan pendengaran yang dapat mempengaruhi kemampuan komunikasi dan pembelajaran anak.',
                        'question_count' => '10 pertanyaan',
                        'duration'   => '5–8 menit',
                        'color'      => '#8D77AB',
                    ]],
                    ['section_key' => 'intelektual', 'label' => 'Hambatan Intelektual', 'sort_order' => 40, 'content' => [
                        'heading'    => 'Hambatan Intelektual',
                        'body'       => 'Skrining untuk mendeteksi hambatan kognitif dan intelektual yang mungkin memerlukan pendampingan khusus dalam proses belajar.',
                        'question_count' => '15 pertanyaan',
                        'duration'   => '8–12 menit',
                        'color'      => '#A86916',
                    ]],
                    ['section_key' => 'faq', 'label' => 'FAQ', 'sort_order' => 50, 'content' => [
                        'heading'  => 'Pertanyaan Umum',
                        'faq_1_q'  => 'Apakah hasil identifikasi ini merupakan diagnosis medis?',
                        'faq_1_a'  => 'Tidak. Hasil identifikasi ini bersifat skrining awal dan bukan diagnosis medis. Untuk diagnosis resmi, konsultasikan dengan dokter atau psikolog anak.',
                        'faq_2_q'  => 'Siapa yang dapat mengisi kuesioner ini?',
                        'faq_2_a'  => 'Orang tua, wali, atau guru yang mengenal dan mengamati perilaku anak sehari-hari.',
                        'faq_3_q'  => 'Apakah data anak saya aman?',
                        'faq_3_a'  => 'Ya. Data yang Anda masukkan tersimpan dengan aman dan hanya dapat diakses oleh Anda.',
                        'faq_4_q'  => 'Berapa lama proses pengisian kuesioner?',
                        'faq_4_a'  => 'Tergantung jenis identifikasi, umumnya antara 5–12 menit.',
                    ]],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // KONTAK
            // ──────────────────────────────────────────────────────────────
            [
                'slug'            => 'kontak',
                'title'           => 'Hubungi Kami',
                'seo_title'       => 'Hubungi InkluSyncID | Kontak & Dukungan',
                'seo_description' => 'Hubungi tim InkluSyncID untuk pertanyaan, dukungan, atau kolaborasi seputar identifikasi anak berkebutuhan khusus.',
                'seo_keywords'    => 'kontak inklusyncid, hubungi kami, dukungan ABK',
                'sections' => [
                    ['section_key' => 'hero', 'label' => 'Hero', 'sort_order' => 10, 'content' => [
                        'eyebrow' => 'Kami Siap Membantu',
                        'heading' => 'Hubungi Tim Kami',
                        'body'    => 'Ada pertanyaan atau butuh bantuan? Tim kami siap membantu Anda.',
                    ]],
                    ['section_key' => 'contact_info', 'label' => 'Informasi Kontak', 'sort_order' => 20, 'content' => [
                        'email'     => 'info@inklusyncid.id',
                        'phone'     => '+62 812-3456-7890',
                        'address'   => 'Jakarta, Indonesia',
                        'instagram' => '@inklusyncid',
                        'hours'     => 'Senin – Jumat, 08.00 – 17.00 WIB',
                    ]],
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            $sections = $pageData['sections'];
            unset($pageData['sections']);

            $page = CmsPage::updateOrCreate(['slug' => $pageData['slug']], $pageData);

            foreach ($sections as $s) {
                CmsSection::updateOrCreate(
                    ['cms_page_id' => $page->id, 'section_key' => $s['section_key']],
                    [
                        'label'      => $s['label'],
                        'sort_order' => $s['sort_order'],
                        'content'    => $s['content'],
                    ]
                );
            }
        }
    }
}
