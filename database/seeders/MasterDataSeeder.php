<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Domain;
use App\Models\Questionnaire;
use App\Models\AnswerOption;
use App\Models\AssessmentRule;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── UPDATE EXISTING ──────────────────────────────
        $this->updateTunagrahita();
        $this->updateTunanetraTotallyBlind();
        $this->updateDisleksia();
        $this->updateSlowLearner();

        // ── DISABILITAS FISIK ────────────────────────────
        $this->seedTunadaksa();

        // ── INTELEKTUAL GENETIK ──────────────────────────
        $this->seedDownSyndrome();
        $this->seedPraderWillieSyndrome();
        $this->seedFragileXSyndrome();
        $this->seedWilliamsSyndrome();

        // ── HAMBATAN BELAJAR SPESIFIK ────────────────────
        $this->seedDisgrafia();
        $this->seedDiskalkulia();

        // ── SENSORIK ─────────────────────────────────────
        $this->seedTunanetraLowVision();
        $this->seedTunarungu();
        $this->seedTunawicara();

        // ── DISABILITAS MENTAL ────────────────────────────
        $this->seedAutism();
        $this->seedADHD();
        $this->seedGangguanEmosiPerilaku();

        // ── DISABILITAS MAJEMUK ───────────────────────────
        $this->seedMDVI();
        $this->seedTunaganda();
    }

    // ═══════════════════════════════════════════════════
    // UPDATE EXISTING CATEGORIES
    // ═══════════════════════════════════════════════════

    private function updateTunagrahita(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'tunagrahita'], [
            'name'                 => 'Tunagrahita',
            'type'                 => 'intelektual',
            'group'                => 'intelektual',
            'description'          => 'Hambatan intelektual yang mempengaruhi kemampuan kognitif, sosial, dan adaptif anak.',
            'icon'                 => 'brain',
            'color'                => '#A86916',
            'result_illustration'  => 'assets/img/hasil-analisa/Tunagrahita.jpg',
            'sort_order'           => 30,
            'is_active'            => true,
        ]);

        $domains = [
            ['name' => 'Domain Konseptual', 'description' => 'Kemampuan bahasa, membaca, menulis, matematika, dan konsep waktu/uang.', 'sort_order' => 1],
            ['name' => 'Domain Sosial',     'description' => 'Kemampuan interpersonal, tanggung jawab sosial, dan mengikuti aturan.', 'sort_order' => 2],
            ['name' => 'Domain Praktis',    'description' => 'Kemampuan merawat diri, pekerjaan rumah, dan kemandirian sehari-hari.', 'sort_order' => 3],
        ];

        foreach ($domains as $d) {
            $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => $d['name']], [
                'description' => $d['description'], 'sort_order' => $d['sort_order'], 'is_active' => true,
            ]);

            $questions = match($d['name']) {
                'Domain Konseptual' => [
                    'Anak kesulitan memahami instruksi verbal sederhana.',
                    'Anak memiliki keterbatasan kosakata dibanding teman sebayanya.',
                    'Anak kesulitan membaca teks sederhana.',
                    'Anak kesulitan menulis kata atau kalimat dasar.',
                    'Anak sulit memahami konsep angka dan berhitung sederhana.',
                    'Anak kesulitan memahami konsep waktu (pagi, siang, malam).',
                    'Anak sulit memahami konsep uang dan jual beli sederhana.',
                ],
                'Domain Sosial' => [
                    'Anak kesulitan menjalin pertemanan dengan teman sebaya.',
                    'Anak sering salah memahami ekspresi atau niat orang lain.',
                    'Anak sulit mengikuti aturan permainan atau tata tertib kelas.',
                    'Anak sulit bertanggung jawab atas tindakannya sendiri.',
                    'Anak mudah dipengaruhi atau dibujuk oleh orang lain.',
                    'Anak kesulitan memahami norma dan perilaku yang sesuai.',
                ],
                'Domain Praktis' => [
                    'Anak membutuhkan bantuan dalam merawat kebersihan diri (mandi, gosok gigi).',
                    'Anak kesulitan berpakaian sendiri tanpa bantuan.',
                    'Anak sulit makan sendiri dengan benar.',
                    'Anak sulit menggunakan fasilitas umum (toilet, transportasi).',
                    'Anak tidak mampu melakukan tugas rumah sederhana.',
                    'Anak kesulitan mengelola barang pribadinya.',
                ],
                default => [],
            };

            foreach ($questions as $i => $q) {
                $questionnaire = Questionnaire::updateOrCreate(
                    ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                    ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
                );
                $this->addYesNoOptions($questionnaire);
            }
        }

        $rules = [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 19,  'color' => '#839986', 'desc' => 'Tidak ada indikasi tunagrahita yang signifikan.', 'rec' => 'Tetap pantau perkembangan anak secara berkala.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light',  'min' => 20, 'max' => 29,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa indikasi hambatan intelektual ringan.', 'rec' => 'Konsultasikan dengan psikolog anak untuk asesmen lebih lanjut.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 30, 'max' => 37,  'color' => '#A86916', 'desc' => 'Terdapat indikasi hambatan intelektual yang perlu penanganan.', 'rec' => 'Segera lakukan asesmen psikologis dan pertimbangkan program pendidikan khusus.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 38, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat adanya hambatan intelektual berat.', 'rec' => 'Rujuk ke psikolog/psikiater anak dan daftarkan ke sekolah luar biasa (SLB).'],
        ];
        $this->insertRules($cat, null, $rules);
    }

    private function updateTunanetraTotallyBlind(): void
    {
        // Migrate old 'tunanetra' slug to 'tunanetra-totally-blind' if it exists
        if (Category::where('slug', 'tunanetra')->exists()) {
            Category::where('slug', 'tunanetra')->update(['slug' => 'tunanetra-totally-blind']);
        }

        $cat = Category::updateOrCreate(['slug' => 'tunanetra-totally-blind'], [
            'name'                => 'Tunanetra (Totally Blind)',
            'type'                => 'sensorik',
            'group'               => 'sensorik_penglihatan',
            'description'         => 'Anak yang mengalami kebutaan total atau hampir total sehingga tidak dapat menggunakan penglihatannya.',
            'icon'                => 'eye',
            'color'               => '#2E5F8A',
            'result_illustration' => 'assets/img/hasil-analisa/Tunanetra totally blind.jpg',
            'sort_order'          => 50,
            'is_active'           => true,
        ]);

        // Existing 12 questions already cover totally blind indicators — no changes to questions/rules
        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0,  'max' => 3,   'color' => '#839986', 'desc' => 'Tidak ada indikasi hambatan penglihatan berat.', 'rec' => 'Tetap lakukan pemeriksaan mata rutin setahun sekali.'],
            ['label' => 'Perlu Perhatian',    'severity_level' => 'light',  'min' => 4,  'max' => 7,   'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda hambatan penglihatan serius.', 'rec' => 'Periksakan ke dokter mata spesialis segera.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 8,  'max' => 10,  'color' => '#A86916', 'desc' => 'Indikasi hambatan penglihatan berat yang perlu penanganan segera.', 'rec' => 'Segera rujuk ke dokter mata dan pertimbangkan orientasi mobilitas.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 11, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat kebutaan total atau hampir total.', 'rec' => 'Rujuk ke dokter mata dan sekolah luar biasa bagian A (SLBA).'],
        ]);
    }

    private function updateDisleksia(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'disleksia'], [
            'name'                => 'Disleksia',
            'type'                => 'akademik',
            'group'               => 'hambatan_belajar',
            'description'         => 'Kesulitan belajar spesifik dalam membaca dan mengeja yang tidak berkaitan dengan kecerdasan.',
            'icon'                => 'book',
            'color'               => '#5C477F',
            'result_illustration' => 'assets/img/hasil-analisa/Diseleksia.jpg',
            'sort_order'          => 31,
            'is_active'           => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Kemampuan Membaca & Menulis'], [
            'description' => 'Indikator kesulitan membaca, menulis, dan mengeja.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Anak sering membalik huruf saat menulis (b/d, p/q, u/n).',
            'Anak kesulitan membaca kata dengan keras dan benar.',
            'Anak membaca sangat lambat dibanding teman sebayanya.',
            'Anak sering kehilangan posisi saat membaca (melompati baris).',
            'Anak kesulitan mengeja kata sederhana.',
            'Anak menghindari aktivitas membaca atau menulis.',
            'Anak sulit mengingat kata yang baru dipelajari.',
            'Anak sulit memahami isi bacaan meski sudah dibaca berulang.',
            'Anak sering menulis kata dengan urutan huruf yang salah.',
            'Anak kesulitan membedakan bunyi huruf yang mirip (b/p, d/t).',
        ];
        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0, 'max' => 2,   'color' => '#839986', 'desc' => 'Tidak ada indikasi disleksia yang signifikan.', 'rec' => 'Tetap pantau kemampuan membaca dan menulis anak.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light',  'min' => 3, 'max' => 5,   'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda disleksia ringan.', 'rec' => 'Konsultasikan dengan guru dan pertimbangkan terapi membaca.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 6, 'max' => 8,   'color' => '#A86916', 'desc' => 'Indikasi disleksia yang perlu intervensi khusus.', 'rec' => 'Lakukan asesmen psikologis dan ikutsertakan dalam program remedial membaca.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 9, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat disleksia berat.', 'rec' => 'Segera rujuk ke psikolog pendidikan dan sekolah dengan program inklusi.'],
        ]);
    }

    private function updateSlowLearner(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'slow-learner'], [
            'name'                => 'Slow Learner',
            'type'                => 'akademik',
            'group'               => 'intelektual',
            'description'         => 'Anak yang memiliki kemampuan akademik di bawah rata-rata namun tidak termasuk tunagrahita.',
            'icon'                => 'clock',
            'color'               => '#839986',
            'result_illustration' => 'assets/img/hasil-analisa/Slow learner.jpg',
            'sort_order'          => 35,
            'is_active'           => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Perkembangan Akademik'], [
            'description' => 'Indikator keterlambatan perkembangan belajar.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Anak membutuhkan waktu lebih lama dari teman sebaya untuk memahami materi baru.',
            'Anak sering perlu penjelasan berulang sebelum memahami instruksi.',
            'Nilai akademis anak secara konsisten di bawah rata-rata kelas.',
            'Anak mudah lupa materi yang baru dipelajari.',
            'Anak kesulitan menyelesaikan tugas dalam batas waktu yang diberikan.',
            'Anak kurang termotivasi dalam belajar karena sering merasa kesulitan.',
            'Anak membutuhkan dukungan ekstra dari guru atau orang tua saat belajar.',
            'Anak kesulitan mengikuti penjelasan guru yang disampaikan dengan cepat.',
            'Anak sering tidak memahami pertanyaan atau soal yang memerlukan pemahaman.',
            'Anak memiliki perhatian yang pendek saat belajar.',
        ];
        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0, 'max' => 2,   'color' => '#839986', 'desc' => 'Tidak ada indikasi slow learner yang signifikan.', 'rec' => 'Tetap berikan dukungan dan motivasi dalam belajar.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light',  'min' => 3, 'max' => 5,   'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda keterlambatan belajar.', 'rec' => 'Berikan bimbingan belajar tambahan dan strategi belajar yang lebih visual.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 6, 'max' => 8,   'color' => '#A86916', 'desc' => 'Indikasi slow learner yang memerlukan intervensi.', 'rec' => 'Konsultasikan dengan psikolog pendidikan dan buat rencana belajar individual.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 9, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat slow learner yang memerlukan perhatian serius.', 'rec' => 'Lakukan asesmen menyeluruh dan pertimbangkan program pendidikan inklusif.'],
        ]);
    }

    // ═══════════════════════════════════════════════════
    // DISABILITAS FISIK
    // ═══════════════════════════════════════════════════

    private function seedTunadaksa(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'tunadaksa'], [
            'name'                => 'Tunadaksa',
            'type'                => 'fisik',
            'group'               => 'fisik',
            'description'         => 'Hambatan fisik atau motorik yang mempengaruhi kemampuan gerak dan fungsi tubuh anak.',
            'icon'                => 'accessibility',
            'color'               => '#5B6A4A',
            'result_illustration' => 'assets/img/hasil-analisa/Tunadaksa.jpg',
            'sort_order'          => 10,
            'is_active'           => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Fungsi Motorik & Gerak'], [
            'description' => 'Kemampuan dan keterbatasan fungsi motorik, gerak, dan kemandirian fisik anak.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Anak mengalami kesulitan menggerakkan anggota tubuh tertentu secara normal.',
            'Anak memiliki keterbatasan dalam berjalan atau bergerak secara mandiri.',
            'Anak membutuhkan alat bantu gerak (kursi roda, kruk, atau kaki palsu).',
            'Anak kesulitan memegang atau menggenggam benda dengan tangan.',
            'Anak mengalami kelumpuhan atau kelemahan otot di bagian tubuh tertentu.',
            'Anak memiliki postur tubuh yang tidak simetris atau tidak normal.',
            'Anak mengalami kesulitan mengontrol gerakan tangan saat menulis atau menggambar.',
            'Anak sering jatuh atau kehilangan keseimbangan saat berjalan.',
            'Anak memiliki keterbatasan dalam mengikuti aktivitas fisik di sekolah.',
            'Anak membutuhkan bantuan orang lain dalam aktivitas merawat diri sehari-hari.',
            'Anak kesulitan menaiki atau menuruni tangga tanpa bantuan.',
            'Anak mengalami nyeri atau ketidaknyamanan fisik saat bergerak.',
            'Anak memiliki salah satu atau beberapa anggota tubuh yang tidak berfungsi normal.',
            'Anak kesulitan duduk tegak dalam waktu lama tanpa sandaran khusus.',
            'Anak membutuhkan penyesuaian fasilitas fisik di sekolah (akses rampa, meja khusus, dll).',
        ];
        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 5,   'color' => '#839986', 'desc' => 'Tidak ada indikasi hambatan fisik yang signifikan.', 'rec' => 'Pantau perkembangan motorik anak secara berkala.'],
            ['label' => 'Perlu Perhatian',   'severity_level' => 'light',  'min' => 6,  'max' => 12,  'color' => '#8D77AB', 'desc' => 'Terdapat indikasi hambatan fisik ringan hingga sedang.', 'rec' => 'Konsultasikan dengan dokter spesialis rehabilitasi medis dan fisioterapis.'],
            ['label' => 'Terindikasi Sedang','severity_level' => 'medium', 'min' => 13, 'max' => 20,  'color' => '#A86916', 'desc' => 'Indikasi hambatan fisik yang memerlukan intervensi terstruktur.', 'rec' => 'Segera rujuk ke dokter spesialis dan pertimbangkan program fisioterapi rutin serta sekolah inklusif.'],
            ['label' => 'Terindikasi Kuat',  'severity_level' => 'heavy',  'min' => 21, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat disabilitas fisik berat yang memerlukan penanganan komprehensif.', 'rec' => 'Rujuk ke tim rehabilitasi medis, pertimbangkan SLB-D, dan optimalkan aksesibilitas lingkungan.'],
        ]);
    }

    // ═══════════════════════════════════════════════════
    // INTELEKTUAL GENETIK — Tunagrahita Nampak Fisik
    // ═══════════════════════════════════════════════════

    private function seedDownSyndrome(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'down-syndrome'], [
            'name'                => 'Down Syndrome',
            'type'                => 'intelektual',
            'group'               => 'intelektual_genetik',
            'description'         => 'Kondisi genetik trisomi 21 yang menyebabkan hambatan intelektual dan ciri fisik khas.',
            'icon'                => 'dna',
            'color'               => '#6B5EA8',
            'result_illustration' => 'assets/img/hasil-analisa/Down syndrome_.jpg',
            'sort_order'          => 20,
            'is_active'           => true,
        ]);

        $domains = [
            [
                'name' => 'Ciri Fisik & Perkembangan',
                'desc' => 'Karakteristik fisik dan keterlambatan perkembangan yang khas pada Down Syndrome.',
                'sort' => 1,
                'questions' => [
                    'Anak memiliki ciri wajah khas Down Syndrome (mata sipit ke atas, hidung pesek, wajah datar).',
                    'Anak memiliki tonus otot yang rendah (hipotonia) sejak lahir.',
                    'Anak mengalami keterlambatan bicara yang signifikan dibanding usia normal.',
                    'Anak memiliki pertumbuhan fisik yang lebih lambat dari teman sebayanya.',
                    'Anak mengalami keterlambatan berjalan dibanding anak seusianya.',
                    'Anak memiliki jari-jari tangan yang pendek dan telapak tangan lebar.',
                    'Anak pernah dikonfirmasi secara medis memiliki kondisi kromosomal.',
                    'Anak memiliki kemampuan kognitif di bawah rata-rata yang teridentifikasi.',
                    'Anak memiliki masalah pendengaran atau penglihatan yang terdeteksi.',
                    'Anak membutuhkan program pendidikan khusus atau inklusif.',
                ],
            ],
            [
                'name' => 'Kemampuan Adaptif & Sosial',
                'desc' => 'Kemampuan adaptasi, interaksi sosial, dan kemandirian dalam kehidupan sehari-hari.',
                'sort' => 2,
                'questions' => [
                    'Anak kesulitan berinteraksi dengan teman sebaya secara wajar.',
                    'Anak sulit mengikuti aturan di lingkungan sekolah.',
                    'Anak memerlukan pendampingan penuh dalam kegiatan belajar.',
                    'Anak menunjukkan keterbatasan dalam kemampuan membaca dan berhitung.',
                    'Anak membutuhkan waktu lebih lama untuk memahami konsep baru.',
                    'Anak kesulitan merawat kebersihan diri secara mandiri.',
                    'Anak membutuhkan bantuan dalam kegiatan sehari-hari (makan, berpakaian).',
                    'Anak memiliki kemampuan bahasa yang terbatas dibanding seusianya.',
                ],
            ],
        ];

        foreach ($domains as $d) {
            $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => $d['name']], [
                'description' => $d['desc'], 'sort_order' => $d['sort'], 'is_active' => true,
            ]);
            foreach ($d['questions'] as $i => $q) {
                $questionnaire = Questionnaire::updateOrCreate(
                    ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                    ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
                );
                $this->addYesNoOptions($questionnaire);
            }
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 9,   'color' => '#839986', 'desc' => 'Indikasi Down Syndrome belum signifikan.', 'rec' => 'Pantau perkembangan anak dan konsultasikan dengan dokter anak.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light', 'min' => 10, 'max' => 19,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa indikator yang perlu perhatian lebih.', 'rec' => 'Konsultasikan dengan dokter spesialis anak dan psikolog untuk asesmen lanjut.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium','min' => 20, 'max' => 29,  'color' => '#A86916', 'desc' => 'Indikasi kuat Down Syndrome yang memerlukan intervensi dini.', 'rec' => 'Lakukan pemeriksaan kromosom dan program stimulasi dini segera.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy', 'min' => 30, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Profil sangat kuat menunjukkan Down Syndrome.', 'rec' => 'Rujuk ke dokter spesialis, lakukan intervensi multi-disiplin, dan daftarkan ke program inklusif atau SLB-C.'],
        ]);
    }

    private function seedPraderWillieSyndrome(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'prader-willie-syndrome'], [
            'name'                => 'Prader-Willi Syndrome',
            'type'                => 'intelektual',
            'group'               => 'intelektual_genetik',
            'description'         => 'Kelainan genetik langka yang menyebabkan hiperfagia, hambatan intelektual, dan disregulasi emosi.',
            'icon'                => 'dna',
            'color'               => '#7A6A8E',
            'result_illustration' => 'assets/img/hasil-analisa/Prader Willie syndrome_.jpg',
            'sort_order'          => 21,
            'is_active'           => true,
        ]);

        $domains = [
            [
                'name' => 'Ciri Fisik & Perilaku Makan',
                'desc' => 'Karakteristik fisik dan pola makan yang khas pada Prader-Willi Syndrome.',
                'sort' => 1,
                'questions' => [
                    'Anak menunjukkan nafsu makan yang sangat besar dan sulit dikendalikan.',
                    'Anak memiliki berat badan berlebih yang tidak proporsional.',
                    'Anak memiliki tonus otot rendah (hipotonia) sejak masa bayi.',
                    'Anak menunjukkan pertumbuhan fisik yang terhambat (perawakan pendek).',
                    'Anak mengambil makanan tanpa izin atau menyembunyikan makanan.',
                    'Anak menunjukkan kemarahan berlebihan ketika akses ke makanan dibatasi.',
                    'Anak memiliki tangan dan kaki yang kecil tidak proporsional dengan tubuh.',
                    'Anak menunjukkan kesulitan dalam koordinasi motorik halus.',
                    'Anak mengalami kantuk berlebihan di siang hari (hipersomnia).',
                    'Anak memiliki warna kulit atau rambut yang lebih terang dari anggota keluarga.',
                ],
            ],
            [
                'name' => 'Kognitif & Regulasi Perilaku',
                'desc' => 'Kemampuan kognitif dan pola perilaku emosional yang khas.',
                'sort' => 2,
                'questions' => [
                    'Anak mengalami keterlambatan perkembangan intelektual yang signifikan.',
                    'Anak menunjukkan ledakan emosi yang tidak proporsional dengan pemicunya.',
                    'Anak memiliki perilaku obsesif atau ritual yang kaku.',
                    'Anak kesulitan beradaptasi dengan perubahan rutinitas.',
                    'Anak mudah frustrasi dan sangat sulit menenangkan diri.',
                    'Anak menunjukkan kurangnya kepekaan terhadap rasa sakit.',
                    'Anak kesulitan mengikuti instruksi yang terdiri dari beberapa langkah.',
                    'Anak membutuhkan dukungan penuh dalam pengambilan keputusan sehari-hari.',
                    'Anak menunjukkan kesulitan belajar yang signifikan di semua mata pelajaran.',
                    'Anak memiliki kemampuan verbal yang relatif baik namun kemampuan praktis sangat terbatas.',
                ],
            ],
        ];

        foreach ($domains as $d) {
            $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => $d['name']], [
                'description' => $d['desc'], 'sort_order' => $d['sort'], 'is_active' => true,
            ]);
            foreach ($d['questions'] as $i => $q) {
                $questionnaire = Questionnaire::updateOrCreate(
                    ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                    ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
                );
                $this->addYesNoOptions($questionnaire);
            }
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 9,   'color' => '#839986', 'desc' => 'Indikasi Prader-Willi Syndrome belum signifikan.', 'rec' => 'Pantau pola makan dan perkembangan anak, konsultasikan dengan dokter anak.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light', 'min' => 10, 'max' => 19,  'color' => '#8D77AB', 'desc' => 'Beberapa indikator hadir, perlu perhatian lebih.', 'rec' => 'Konsultasikan dengan dokter spesialis anak dan ahli gizi.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium','min' => 20, 'max' => 29,  'color' => '#A86916', 'desc' => 'Pola indikator cukup kuat mengarah ke Prader-Willi Syndrome.', 'rec' => 'Lakukan pemeriksaan genetik dan program manajemen diet yang ketat.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy', 'min' => 30, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Profil sangat kuat menunjukkan Prader-Willi Syndrome.', 'rec' => 'Rujuk ke genetisis medis, endokrinologis, dan tim multi-disiplin untuk penanganan komprehensif.'],
        ]);
    }

    private function seedFragileXSyndrome(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'fragile-x-syndrome'], [
            'name'                => 'Fragile X Syndrome',
            'type'                => 'intelektual',
            'group'               => 'intelektual_genetik',
            'description'         => 'Gangguan genetik terkait kromosom X yang menyebabkan hambatan intelektual dan hipersensitivitas sensorik.',
            'icon'                => 'dna',
            'color'               => '#8E5E7A',
            'result_illustration' => 'assets/img/hasil-analisa/Fragile x syndrome_.jpg',
            'sort_order'          => 22,
            'is_active'           => true,
        ]);

        $domains = [
            [
                'name' => 'Ciri Fisik & Sensorik',
                'desc' => 'Karakteristik fisik dan respons sensorik khas Fragile X Syndrome.',
                'sort' => 1,
                'questions' => [
                    'Anak memiliki wajah yang memanjang dan telinga yang relatif besar.',
                    'Anak memiliki sendi yang sangat fleksibel (hipermobilitas sendi).',
                    'Anak menunjukkan kepekaan berlebihan terhadap rangsangan sensorik (suara, tekstur, cahaya).',
                    'Anak menghindari kontak mata saat berbicara dengan orang lain.',
                    'Anak menunjukkan gerakan berulang seperti mengepak tangan (hand flapping).',
                    'Anak mudah teralihkan oleh suara atau gerakan di sekitar.',
                    'Anak menghindari keramaian atau situasi yang penuh stimulasi sensorik.',
                    'Anak memiliki nada bicara yang khas — cepat, berulang, atau tidak teratur.',
                    'Anak menunjukkan kecemasan sosial yang tinggi di lingkungan baru.',
                    'Anak sangat sensitif terhadap perubahan atau kejutan yang tidak terduga.',
                ],
            ],
            [
                'name' => 'Kognitif & Sosial',
                'desc' => 'Kemampuan kognitif dan interaksi sosial pada Fragile X Syndrome.',
                'sort' => 2,
                'questions' => [
                    'Anak mengalami keterlambatan bahasa (bicara terlambat atau tidak jelas).',
                    'Anak menunjukkan kesulitan belajar yang signifikan di sekolah.',
                    'Anak kesulitan berinteraksi timbal-balik dengan teman sebaya.',
                    'Anak menunjukkan perilaku mirip autisme (stimming, ekolalia).',
                    'Anak kesulitan mempertahankan perhatian dalam waktu yang lama.',
                    'Anak menunjukkan impulsivitas dan hiperaktivitas yang tinggi.',
                    'Anak kesulitan memahami konsep-konsep abstrak.',
                    'Anak memiliki memori jangka pendek yang buruk.',
                    'Anak menunjukkan kecemasan yang berlebihan dalam situasi baru atau perubahan.',
                    'Anak membutuhkan dukungan penuh dalam semua kegiatan akademik.',
                ],
            ],
        ];

        foreach ($domains as $d) {
            $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => $d['name']], [
                'description' => $d['desc'], 'sort_order' => $d['sort'], 'is_active' => true,
            ]);
            foreach ($d['questions'] as $i => $q) {
                $questionnaire = Questionnaire::updateOrCreate(
                    ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                    ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
                );
                $this->addYesNoOptions($questionnaire);
            }
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 9,   'color' => '#839986', 'desc' => 'Indikasi Fragile X Syndrome belum signifikan.', 'rec' => 'Pantau perkembangan anak, konsultasikan bila ada kekhawatiran.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light', 'min' => 10, 'max' => 19,  'color' => '#8D77AB', 'desc' => 'Beberapa indikator hadir yang perlu evaluasi lebih lanjut.', 'rec' => 'Konsultasikan dengan psikolog anak dan dokter perkembangan.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium','min' => 20, 'max' => 29,  'color' => '#A86916', 'desc' => 'Pola indikator kuat mengarah ke Fragile X Syndrome.', 'rec' => 'Lakukan pemeriksaan genetik molekuler dan program intervensi dini.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy', 'min' => 30, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Profil sangat kuat menunjukkan Fragile X Syndrome.', 'rec' => 'Rujuk ke genetisis dan tim multi-disiplin, termasuk terapi wicara dan integrasi sensorik.'],
        ]);
    }

    private function seedWilliamsSyndrome(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'williams-syndrome'], [
            'name'                => 'Williams Syndrome',
            'type'                => 'intelektual',
            'group'               => 'intelektual_genetik',
            'description'         => 'Kelainan genetik langka dengan ciri khas kepribadian sangat ramah, hambatan kognitif, dan hipersensitivitas suara.',
            'icon'                => 'dna',
            'color'               => '#6E8A9E',
            'result_illustration' => 'assets/img/hasil-analisa/William syndrome.jpg',
            'sort_order'          => 23,
            'is_active'           => true,
        ]);

        $domains = [
            [
                'name' => 'Ciri Fisik & Kepribadian',
                'desc' => 'Karakteristik fisik dan profil kepribadian khas Williams Syndrome.',
                'sort' => 1,
                'questions' => [
                    'Anak memiliki wajah khas: hidung pesek, pipi penuh, mulut lebar, dengan ekspresi ramah.',
                    'Anak menunjukkan kepribadian yang sangat ramah dan mudah bergaul dengan siapa saja, termasuk orang asing.',
                    'Anak memiliki kepekaan berlebihan terhadap suara tertentu (hyperacusis).',
                    'Anak menunjukkan ketakutan berlebihan atau kecemasan terhadap suara keras.',
                    'Anak memiliki kemampuan musik, vokal, atau ritme yang menonjol.',
                    'Anak memiliki perawakan yang lebih pendek dari rata-rata usianya.',
                    'Anak memiliki riwayat masalah jantung atau kardiovaskuler.',
                    'Anak kesulitan membedakan bahaya sosial (terlalu percaya pada orang asing).',
                    'Anak menunjukkan perhatian yang sangat intens terhadap wajah orang lain.',
                    'Anak memiliki koordinasi motorik kasar yang buruk.',
                ],
            ],
            [
                'name' => 'Kognitif & Akademik',
                'desc' => 'Profil kognitif unik dengan kekuatan verbal namun kelemahan visuospatial.',
                'sort' => 2,
                'questions' => [
                    'Anak memiliki keterlambatan kognitif umum namun kemampuan bahasa relatif lebih baik.',
                    'Anak menunjukkan kesulitan besar dalam tugas visuospatial (menggambar, puzzle, membaca peta).',
                    'Anak kesulitan dalam matematika dan pemahaman konsep angka.',
                    'Anak mudah terdistraksi dan sulit mempertahankan fokus dalam belajar.',
                    'Anak menunjukkan kecemasan dan kekhawatiran yang berlebihan di luar konteks.',
                    'Anak membutuhkan pengulangan dan bimbingan konsisten dalam belajar.',
                    'Anak bergantung pada rutinitas dan sangat terganggu oleh perubahan mendadak.',
                    'Anak kesulitan dalam perencanaan dan penyelesaian masalah sehari-hari.',
                    'Anak memiliki daya ingat verbal yang baik namun kurang mampu menerapkannya secara praktis.',
                    'Anak membutuhkan dukungan ekstra untuk mengikuti kurikulum reguler.',
                ],
            ],
        ];

        foreach ($domains as $d) {
            $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => $d['name']], [
                'description' => $d['desc'], 'sort_order' => $d['sort'], 'is_active' => true,
            ]);
            foreach ($d['questions'] as $i => $q) {
                $questionnaire = Questionnaire::updateOrCreate(
                    ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                    ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
                );
                $this->addYesNoOptions($questionnaire);
            }
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 9,   'color' => '#839986', 'desc' => 'Indikasi Williams Syndrome belum signifikan.', 'rec' => 'Pantau perkembangan sosial dan kognitif anak secara berkala.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light', 'min' => 10, 'max' => 19,  'color' => '#8D77AB', 'desc' => 'Beberapa indikator hadir yang perlu evaluasi lebih lanjut.', 'rec' => 'Konsultasikan dengan dokter spesialis anak dan neuropsikolog.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium','min' => 20, 'max' => 29,  'color' => '#A86916', 'desc' => 'Pola indikator cukup kuat mengarah ke Williams Syndrome.', 'rec' => 'Lakukan pemeriksaan genetik dan program intervensi dini yang menyeluruh.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy', 'min' => 30, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Profil sangat kuat menunjukkan Williams Syndrome.', 'rec' => 'Rujuk ke tim multi-disiplin termasuk kardiologis anak, ahli saraf, dan terapi integrasi sensorik.'],
        ]);
    }

    // ═══════════════════════════════════════════════════
    // HAMBATAN BELAJAR SPESIFIK
    // ═══════════════════════════════════════════════════

    private function seedDisgrafia(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'disgrafia'], [
            'name'                => 'Disgrafia',
            'type'                => 'akademik',
            'group'               => 'hambatan_belajar',
            'description'         => 'Kesulitan belajar spesifik dalam menulis yang berkaitan dengan motorik halus dan representasi tulisan.',
            'icon'                => 'pencil',
            'color'               => '#8B6E4E',
            'result_illustration' => 'assets/img/hasil-analisa/Disgrafia.jpg',
            'sort_order'          => 32,
            'is_active'           => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Kemampuan Menulis'], [
            'description' => 'Indikator kesulitan dalam proses dan hasil tulisan tangan.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Tulisan tangan anak sangat sulit dibaca oleh orang lain, bahkan oleh guru.',
            'Anak memegang pensil atau pena dengan cara yang tidak lazim atau tidak efektif.',
            'Anak mengeluh tangan terasa sakit atau lelah setelah menulis sedikit.',
            'Anak mencampur huruf kapital dan huruf kecil secara acak dalam satu kata.',
            'Anak menulis huruf atau angka secara terbalik meskipun sudah di kelas tinggi.',
            'Anak kesulitan menulis di dalam garis atau mengatur jarak antar huruf/kata.',
            'Anak membutuhkan waktu jauh lebih lama dari teman sebayanya untuk menyelesaikan tulisan.',
            'Anak sering menghapus tulisannya berkali-kali karena tidak puas dengan hasilnya.',
            'Anak kesulitan menyalin tulisan dari papan tulis ke buku dengan akurat.',
            'Anak memiliki ukuran dan bentuk huruf yang tidak konsisten dalam satu baris.',
            'Anak cenderung menghindari tugas yang mengharuskan menulis dalam jumlah panjang.',
            'Anak berbicara sendiri atau melafalkan saat menulis sebagai panduan dirinya.',
        ];
        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0,  'max' => 3,   'color' => '#839986', 'desc' => 'Tidak ada indikasi disgrafia yang signifikan.', 'rec' => 'Tetap pantau kualitas tulisan anak dan berikan latihan motorik halus.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light',  'min' => 4,  'max' => 9,   'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda disgrafia ringan.', 'rec' => 'Konsultasikan dengan terapis okupasi dan berikan latihan menulis terstruktur.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 10, 'max' => 16,  'color' => '#A86916', 'desc' => 'Indikasi disgrafia yang memerlukan intervensi khusus.', 'rec' => 'Lakukan asesmen terapis okupasi dan pertimbangkan alat tulis adaptif serta remedial menulis.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 17, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat disgrafia berat yang sangat menghambat.', 'rec' => 'Rujuk ke psikolog pendidikan dan terapis okupasi untuk program intervensi intensif.'],
        ]);
    }

    private function seedDiskalkulia(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'diskalkulia'], [
            'name'                => 'Diskalkulia',
            'type'                => 'akademik',
            'group'               => 'hambatan_belajar',
            'description'         => 'Kesulitan belajar spesifik dalam matematika dan pemrosesan angka yang tidak berkaitan dengan kecerdasan.',
            'icon'                => 'calculator',
            'color'               => '#6B8E6E',
            'result_illustration' => 'assets/img/hasil-analisa/Diskalkulia.jpg',
            'sort_order'          => 33,
            'is_active'           => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Kemampuan Numerasi & Matematika'], [
            'description' => 'Indikator kesulitan pemrosesan angka, operasi hitung, dan pemahaman konsep matematika.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Anak kesulitan mengenali dan membedakan simbol angka (seperti 3 vs 8, 6 vs 9).',
            'Anak tidak dapat menghitung maju atau mundur secara berurutan dengan benar.',
            'Anak kesulitan memahami nilai tempat (satuan, puluhan, ratusan).',
            'Anak sangat lambat dalam operasi hitung dasar meski sudah dibantu.',
            'Anak sering membingungkan jenis operasi hitung yang harus digunakan (+, −, ×, ÷).',
            'Anak tidak dapat memperkirakan jumlah atau membandingkan besaran.',
            'Anak kesulitan membaca jam analog atau menggunakan uang dalam situasi nyata.',
            'Anak sulit mengingat fakta matematika dasar seperti tabel perkalian.',
            'Anak menghindari permainan atau aktivitas yang melibatkan angka.',
            'Anak masih menghitung dengan jari meskipun sudah berada di kelas tinggi.',
            'Anak kesulitan memahami konsep lebih banyak, lebih sedikit, atau sama dengan.',
            'Anak tidak dapat menyelesaikan soal cerita matematika sederhana.',
        ];
        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0,  'max' => 3,   'color' => '#839986', 'desc' => 'Tidak ada indikasi diskalkulia yang signifikan.', 'rec' => 'Berikan pendekatan belajar matematika yang lebih konkret dan visual.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light',  'min' => 4,  'max' => 9,   'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda diskalkulia ringan.', 'rec' => 'Konsultasikan dengan guru matematika dan pertimbangkan bimbingan belajar khusus numerasi.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 10, 'max' => 16,  'color' => '#A86916', 'desc' => 'Indikasi diskalkulia yang perlu intervensi terstruktur.', 'rec' => 'Lakukan asesmen psikologis dan program remedial matematika dengan metode multi-sensorik.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 17, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat diskalkulia berat.', 'rec' => 'Rujuk ke psikolog pendidikan dan gunakan alat bantu hitung adaptif dalam pembelajaran.'],
        ]);
    }

    // ═══════════════════════════════════════════════════
    // DISABILITAS SENSORIK
    // ═══════════════════════════════════════════════════

    private function seedTunanetraLowVision(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'tunanetra-low-vision'], [
            'name'                => 'Tunanetra (Low Vision)',
            'type'                => 'sensorik',
            'group'               => 'sensorik_penglihatan',
            'description'         => 'Hambatan penglihatan berat namun masih memiliki sisa penglihatan fungsional yang dapat dioptimalkan.',
            'icon'                => 'eye',
            'color'               => '#2A6A8F',
            'result_illustration' => 'assets/img/hasil-analisa/Tunanetra low vision.jpg',
            'sort_order'          => 51,
            'is_active'           => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Fungsi Penglihatan Parsial'], [
            'description' => 'Kemampuan dan keterbatasan fungsi sisa penglihatan anak.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Anak masih dapat melihat tetapi penglihatan sangat terbatas meski menggunakan kacamata.',
            'Anak harus mendekatkan wajah sangat dekat (kurang dari 30 cm) untuk membaca teks.',
            'Anak mengandalkan alat pembesar (kaca pembesar) untuk dapat membaca.',
            'Anak kesulitan melihat atau mengenali benda yang berjarak lebih dari 2 meter.',
            'Anak memiliki lapang pandang yang menyempit seperti melihat melalui terowongan.',
            'Anak kesulitan melihat dalam kondisi pencahayaan yang kurang atau remang.',
            'Anak kesulitan membedakan objek dengan kontras rendah (teks abu di latar putih).',
            'Anak dapat mengenali wajah dari jarak dekat namun tidak dari jarak jauh.',
            'Anak membutuhkan ukuran teks yang sangat besar agar dapat membaca.',
            'Anak memiliki sensitivitas tinggi terhadap cahaya terang (mudah silau).',
            'Anak membutuhkan waktu lebih lama untuk beradaptasi dari kondisi gelap ke terang.',
            'Anak masih dapat menggunakan sisa penglihatan untuk navigasi dan kegiatan dasar.',
        ];
        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0,  'max' => 3,   'color' => '#839986', 'desc' => 'Tidak ada indikasi low vision yang signifikan.', 'rec' => 'Lakukan pemeriksaan mata rutin secara berkala.'],
            ['label' => 'Perlu Perhatian',    'severity_level' => 'light',  'min' => 4,  'max' => 8,   'color' => '#8D77AB', 'desc' => 'Terdapat indikasi hambatan penglihatan parsial.', 'rec' => 'Periksakan ke dokter mata spesialis dan pertimbangkan alat bantu optik.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 9,  'max' => 16,  'color' => '#A86916', 'desc' => 'Indikasi low vision yang perlu penanganan dan akomodasi segera.', 'rec' => 'Gunakan alat bantu penglihatan, modifikasi materi belajar (teks besar), dan konsultasi ke layanan low vision.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 17, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi low vision berat yang sangat mempengaruhi fungsi sehari-hari.', 'rec' => 'Rujuk ke klinik low vision, program orientasi mobilitas, dan sekolah dengan fasilitas inklusif.'],
        ]);
    }

    private function seedTunarungu(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'tunarungu'], [
            'name'                => 'Tunarungu',
            'type'                => 'sensorik',
            'group'               => 'sensorik_pendengaran',
            'description'         => 'Hambatan pendengaran yang mempengaruhi komunikasi, perkembangan bahasa, dan interaksi sosial anak.',
            'icon'                => 'ear',
            'color'               => '#3D7A6E',
            'result_illustration' => 'assets/img/hasil-analisa/Tunarungu.jpg',
            'sort_order'          => 55,
            'is_active'           => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Fungsi Pendengaran & Komunikasi'], [
            'description' => 'Kemampuan pendengaran, respons terhadap suara, dan komunikasi verbal.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Anak tidak merespons ketika dipanggil dari arah belakang tanpa ada kontak visual.',
            'Anak sering meminta orang lain untuk mengulangi perkataan mereka.',
            'Anak mengandalkan membaca gerakan bibir saat berkomunikasi.',
            'Anak berbicara dengan volume yang terlalu keras atau tidak teratur.',
            'Anak kesulitan mengikuti percakapan dalam kelompok atau diskusi kelas.',
            'Anak lebih banyak menggunakan bahasa isyarat atau gesture daripada bicara.',
            'Anak sering tidak menyadari suara di sekitarnya (alarm, bel sekolah, panggilan).',
            'Anak menunjukkan keterlambatan perkembangan bahasa lisan yang signifikan.',
            'Anak menggunakan atau telah dirujuk untuk menggunakan alat bantu dengar.',
            'Anak kesulitan mengikuti instruksi yang disampaikan secara lisan saja.',
            'Memiliki riwayat pemeriksaan audiologi dengan hasil gangguan pendengaran.',
            'Anak lebih mudah berkomunikasi melalui tulisan atau media visual.',
            'Anak sering salah memahami kata-kata yang memiliki bunyi mirip.',
            'Anak menunjukkan frustrasi saat tidak dapat memahami atau dipahami orang lain.',
        ];
        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0,  'max' => 4,   'color' => '#839986', 'desc' => 'Tidak ada indikasi hambatan pendengaran yang signifikan.', 'rec' => 'Tetap lakukan pemeriksaan pendengaran rutin secara berkala.'],
            ['label' => 'Perlu Perhatian',    'severity_level' => 'light',  'min' => 5,  'max' => 10,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda hambatan pendengaran.', 'rec' => 'Lakukan pemeriksaan audiologi dan konsultasikan dengan dokter THT.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 11, 'max' => 18,  'color' => '#A86916', 'desc' => 'Indikasi tunarungu yang memerlukan penanganan segera.', 'rec' => 'Segera lakukan asesmen audiologi, pertimbangkan alat bantu dengar, dan program terapi wicara.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 19, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat tunarungu berat yang sangat mempengaruhi komunikasi.', 'rec' => 'Rujuk ke audiologis, pertimbangkan implan koklea, dan daftarkan ke SLB-B atau sekolah inklusif dengan dukungan komunikasi.'],
        ]);
    }

    private function seedTunawicara(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'tunawicara'], [
            'name'                => 'Tunawicara',
            'type'                => 'sensorik',
            'group'               => 'sensorik_wicara',
            'description'         => 'Hambatan pada kemampuan berbicara yang mempengaruhi komunikasi ekspresif anak.',
            'icon'                => 'message',
            'color'               => '#7A4E6B',
            'result_illustration' => null,
            'sort_order'          => 60,
            'is_active'           => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Kemampuan Bicara & Komunikasi Ekspresif'], [
            'description' => 'Kemampuan anak dalam menyampaikan pikiran dan kebutuhan melalui bicara.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Anak tidak dapat berbicara sama sekali atau hanya mengeluarkan suara tanpa kata.',
            'Bicara anak sangat tidak jelas sehingga sulit dipahami oleh orang yang baru mengenalnya.',
            'Anak menggunakan gesture, isyarat, atau gambar sebagai pengganti utama bicara.',
            'Anak menunjukkan hambatan bicara seperti gagap (stuttering) yang mengganggu komunikasi.',
            'Anak kesulitan mengucapkan konsonan atau kelompok kata tertentu dengan benar.',
            'Anak menghindari situasi yang mengharuskan berbicara di depan orang lain.',
            'Anak menggunakan kosakata yang sangat terbatas dibanding usia seharusnya.',
            'Anak memiliki gangguan suara yang konsisten (serak, terlalu pelan, atau sangat keras).',
            'Anak tidak dapat menyusun kalimat lengkap dan hanya menggunakan kata tunggal.',
            'Anak membutuhkan alat bantu komunikasi (AAC) seperti PECS atau tablet komunikasi.',
            'Anak menunjukkan frustrasi besar ketika tidak dapat menyampaikan keinginan atau pikirannya.',
            'Anak memiliki riwayat terapi wicara atau sudah dirujuk ke terapis wicara.',
        ];
        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0,  'max' => 3,   'color' => '#839986', 'desc' => 'Tidak ada indikasi hambatan wicara yang signifikan.', 'rec' => 'Pantau perkembangan bahasa anak secara berkala.'],
            ['label' => 'Perlu Perhatian',    'severity_level' => 'light',  'min' => 4,  'max' => 9,   'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda hambatan wicara.', 'rec' => 'Konsultasikan dengan terapis wicara untuk evaluasi kemampuan bicara.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 10, 'max' => 16,  'color' => '#A86916', 'desc' => 'Indikasi hambatan wicara yang perlu intervensi terapi.', 'rec' => 'Mulai program terapi wicara rutin dan pertimbangkan AAC sebagai alat bantu komunikasi sementara.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 17, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat hambatan wicara berat.', 'rec' => 'Rujuk ke terapis wicara dan audiologis, implementasikan AAC secara penuh, dan libatkan psikolog komunikasi.'],
        ]);
    }

    // ═══════════════════════════════════════════════════
    // DISABILITAS MENTAL
    // ═══════════════════════════════════════════════════

    private function seedAutism(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'autism'], [
            'name'                => 'Autism (ASD)',
            'type'                => 'mental',
            'group'               => 'mental_autism',
            'description'         => 'Gangguan perkembangan spektrum yang mempengaruhi komunikasi sosial, perilaku, dan pemrosesan sensorik.',
            'icon'                => 'puzzle',
            'color'               => '#4A7A9B',
            'result_illustration' => 'assets/img/hasil-analisa/Autism.jpg',
            'sort_order'          => 70,
            'is_active'           => true,
        ]);

        $domains = [
            [
                'name' => 'Interaksi Sosial',
                'desc' => 'Kemampuan anak dalam membangun dan mempertahankan hubungan sosial.',
                'sort' => 1,
                'questions' => [
                    'Anak menghindari kontak mata saat berbicara dengan orang lain.',
                    'Anak kesulitan bermain bersama teman sebaya secara timbal-balik.',
                    'Anak tidak merespons atau terlambat merespons ketika namanya dipanggil.',
                    'Anak kesulitan memahami dan mengungkapkan emosi secara tepat.',
                    'Anak menunjukkan ekspresi wajah yang datar atau tidak sesuai konteks.',
                    'Anak lebih suka bermain sendiri daripada bermain bersama orang lain.',
                    'Anak kesulitan memahami peraturan tidak tertulis dalam pergaulan.',
                    'Anak tidak meniru perilaku sosial orang lain secara spontan.',
                    'Anak tidak menunjukkan respons yang wajar terhadap pujian atau teguran.',
                    'Anak tidak dapat mempertahankan percakapan dua arah.',
                ],
            ],
            [
                'name' => 'Komunikasi',
                'desc' => 'Kemampuan komunikasi verbal dan non-verbal anak.',
                'sort' => 2,
                'questions' => [
                    'Anak mengalami keterlambatan perkembangan bicara yang signifikan.',
                    'Anak mengulangi kata atau kalimat orang lain (ekolalia).',
                    'Anak menggunakan bahasa yang kaku, terlalu formal, atau aneh.',
                    'Anak tidak memulai komunikasi atau percakapan secara spontan.',
                    'Anak tidak menggunakan gestur seperti menunjuk atau melambai secara spontan.',
                    'Anak bicara dengan nada monoton tanpa variasi emosi.',
                    'Anak memahami kata-kata secara harfiah dan kesulitan dengan ungkapan kiasan.',
                    'Anak pernah kehilangan kemampuan bahasa yang sudah diperoleh sebelumnya.',
                ],
            ],
            [
                'name' => 'Perilaku & Minat',
                'desc' => 'Pola perilaku berulang, minat terbatas, dan respons sensorik.',
                'sort' => 3,
                'questions' => [
                    'Anak memiliki rutinitas yang sangat kaku dan sangat terganggu ketika rutinitas berubah.',
                    'Anak memiliki ketertarikan yang sangat intens pada satu topik atau objek tertentu.',
                    'Anak melakukan gerakan berulang (mengepak tangan, berputar, mengayun tubuh).',
                    'Anak menunjukkan kepekaan berlebihan atau sangat kurang terhadap rangsangan sensorik.',
                    'Anak sangat sensitif terhadap tekstur tertentu, suara keras, atau bau spesifik.',
                    'Anak memperhatikan detail kecil yang diabaikan orang lain.',
                    'Anak sangat sulit beradaptasi ketika lingkungan atau jadwal berubah mendadak.',
                ],
            ],
        ];

        foreach ($domains as $d) {
            $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => $d['name']], [
                'description' => $d['desc'], 'sort_order' => $d['sort'], 'is_active' => true,
            ]);
            foreach ($d['questions'] as $i => $q) {
                $questionnaire = Questionnaire::updateOrCreate(
                    ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                    ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
                );
                $this->addYesNoOptions($questionnaire);
            }
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 11,  'color' => '#839986', 'desc' => 'Tidak ada indikasi autisme yang signifikan.', 'rec' => 'Pantau perkembangan sosial dan komunikasi anak secara berkala.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light', 'min' => 12, 'max' => 22,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa indikator yang perlu evaluasi lebih lanjut.', 'rec' => 'Konsultasikan dengan psikolog anak atau dokter perkembangan untuk asesmen ASD.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium','min' => 23, 'max' => 33,  'color' => '#A86916', 'desc' => 'Indikasi autisme cukup kuat yang memerlukan intervensi dini.', 'rec' => 'Lakukan asesmen komprehensif ASD, program ABA atau terapi berbasis bukti lainnya.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy', 'min' => 34, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat autisme yang memerlukan penanganan intensif.', 'rec' => 'Rujuk ke psikiater anak atau tim diagnostik ASD, program intervensi intensif, dan sekolah inklusif atau khusus.'],
        ]);
    }

    private function seedADHD(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'adhd'], [
            'name'                => 'ADHD',
            'type'                => 'mental',
            'group'               => 'mental_adhd',
            'description'         => 'Gangguan pemusatan perhatian dan hiperaktivitas yang mempengaruhi konsentrasi, impulsivitas, dan aktivitas anak.',
            'icon'                => 'zap',
            'color'               => '#8E6B2E',
            'result_illustration' => 'assets/img/hasil-analisa/Adhd.jpg',
            'sort_order'          => 75,
            'is_active'           => true,
        ]);

        $domains = [
            [
                'name' => 'Ketidakperhatian (Inattention)',
                'desc' => 'Kesulitan dalam mempertahankan perhatian dan menyelesaikan tugas.',
                'sort' => 1,
                'questions' => [
                    'Anak sering gagal memperhatikan detail atau membuat kesalahan ceroboh dalam tugas.',
                    'Anak sulit mempertahankan perhatian saat mengerjakan tugas atau saat bermain.',
                    'Anak tampak tidak mendengarkan ketika diajak bicara langsung.',
                    'Anak sering tidak mengikuti instruksi dan gagal menyelesaikan tugas hingga selesai.',
                    'Anak kesulitan mengorganisir tugas dan aktivitas secara teratur.',
                    'Anak menghindari atau enggan mengerjakan tugas yang memerlukan usaha mental terus-menerus.',
                    'Anak sering kehilangan barang yang dibutuhkan (buku, pensil, penghapus).',
                    'Anak mudah terdistraksi oleh rangsangan atau kejadian di sekitar yang tidak relevan.',
                    'Anak sering lupa dalam kegiatan dan rutinitas sehari-hari.',
                ],
            ],
            [
                'name' => 'Hiperaktivitas & Impulsivitas',
                'desc' => 'Pola hiperaktivitas motorik dan kontrol impuls yang rendah.',
                'sort' => 2,
                'questions' => [
                    'Anak sering menggerakkan tangan atau kaki atau tidak bisa diam di kursi.',
                    'Anak sering meninggalkan kursi di kelas ketika seharusnya tetap duduk.',
                    'Anak berlari-lari atau memanjat secara berlebihan dalam situasi yang tidak tepat.',
                    'Anak kesulitan bermain atau terlibat dalam kegiatan santai dengan tenang.',
                    'Anak tampak selalu bergerak, seolah digerakkan oleh mesin.',
                    'Anak sering berbicara terlalu banyak tanpa bisa mengendalikan diri.',
                    'Anak sering menjawab pertanyaan sebelum pertanyaan selesai diajukan.',
                    'Anak kesulitan menunggu giliran dalam permainan atau antrian.',
                    'Anak sering menginterupsi atau mengganggu kegiatan orang lain.',
                ],
            ],
        ];

        foreach ($domains as $d) {
            $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => $d['name']], [
                'description' => $d['desc'], 'sort_order' => $d['sort'], 'is_active' => true,
            ]);
            foreach ($d['questions'] as $i => $q) {
                $questionnaire = Questionnaire::updateOrCreate(
                    ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                    ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
                );
                $this->addYesNoOptions($questionnaire);
            }
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 7,   'color' => '#839986', 'desc' => 'Tidak ada indikasi ADHD yang signifikan.', 'rec' => 'Pantau kemampuan konsentrasi dan perilaku anak secara berkala.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light', 'min' => 8,  'max' => 15,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa indikator ADHD yang perlu perhatian.', 'rec' => 'Konsultasikan dengan psikolog anak dan terapkan strategi manajemen perilaku di kelas.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium','min' => 16, 'max' => 23,  'color' => '#A86916', 'desc' => 'Indikasi ADHD yang cukup kuat dan mempengaruhi fungsi sehari-hari.', 'rec' => 'Lakukan asesmen ADHD komprehensif, pertimbangkan intervensi perilaku terstruktur dan modifikasi lingkungan.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy', 'min' => 24, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat ADHD yang sangat mempengaruhi fungsi akademik dan sosial.', 'rec' => 'Rujuk ke psikiater atau neurolog anak untuk asesmen dan kemungkinan intervensi medis.'],
        ]);
    }

    private function seedGangguanEmosiPerilaku(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'gangguan-emosi-perilaku'], [
            'name'                => 'Gangguan Emosi & Perilaku',
            'type'                => 'mental',
            'group'               => 'mental_emosi',
            'description'         => 'Hambatan regulasi emosi dan pola perilaku yang mengganggu fungsi sosial dan akademik anak.',
            'icon'                => 'heart',
            'color'               => '#8A3A3A',
            'result_illustration' => 'assets/img/hasil-analisa/Gangguan emosi dan tingkah laku.jpg',
            'sort_order'          => 80,
            'is_active'           => true,
        ]);

        $domains = [
            [
                'name' => 'Regulasi Emosi',
                'desc' => 'Kemampuan mengelola dan mengekspresikan emosi secara tepat.',
                'sort' => 1,
                'questions' => [
                    'Anak menunjukkan kesedihan atau kemurungan yang berkepanjangan tanpa sebab jelas.',
                    'Anak mengalami perubahan suasana hati yang ekstrem dan tiba-tiba.',
                    'Anak menunjukkan kecemasan berlebihan yang menghambat aktivitas belajar.',
                    'Anak sering mengeluh sakit fisik (pusing, mual) tanpa penyebab medis yang jelas.',
                    'Anak menarik diri dari lingkungan sosial dan kehilangan minat pada aktivitas.',
                    'Anak menunjukkan rasa takut yang tidak proporsional terhadap objek atau situasi tertentu.',
                    'Anak mudah menangis atau marah dalam situasi yang tidak memicu hal tersebut pada anak lain.',
                    'Anak menunjukkan tanda-tanda pengalaman emosional traumatis yang belum terselesaikan.',
                ],
            ],
            [
                'name' => 'Pola Perilaku',
                'desc' => 'Pola perilaku yang mengganggu diri sendiri, orang lain, atau lingkungan.',
                'sort' => 2,
                'questions' => [
                    'Anak sering menantang, membantah, atau menolak permintaan orang dewasa.',
                    'Anak menunjukkan agresi fisik (memukul, menendang, menggigit) terhadap orang lain.',
                    'Anak merusak barang-barang milik diri sendiri atau orang lain secara sengaja.',
                    'Anak berbohong atau mengambil barang milik orang lain secara berulang.',
                    'Anak mengganggu atau mengintimidasi teman sebaya secara konsisten (bullying).',
                    'Anak menunjukkan perilaku menyakiti diri sendiri.',
                    'Anak terlibat dalam perilaku berisiko tanpa menyadari konsekuensinya.',
                    'Anak menunjukkan kurangnya empati terhadap perasaan atau kesulitan orang lain.',
                ],
            ],
        ];

        foreach ($domains as $d) {
            $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => $d['name']], [
                'description' => $d['desc'], 'sort_order' => $d['sort'], 'is_active' => true,
            ]);
            foreach ($d['questions'] as $i => $q) {
                $questionnaire = Questionnaire::updateOrCreate(
                    ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                    ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
                );
                $this->addYesNoOptions($questionnaire);
            }
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 6,   'color' => '#839986', 'desc' => 'Tidak ada indikasi gangguan emosi dan perilaku yang signifikan.', 'rec' => 'Pantau perkembangan emosi dan perilaku anak secara berkala.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light', 'min' => 7,  'max' => 13,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa indikator yang perlu perhatian lebih.', 'rec' => 'Konsultasikan dengan psikolog anak dan pertimbangkan konseling keluarga.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium','min' => 14, 'max' => 20,  'color' => '#A86916', 'desc' => 'Indikasi gangguan emosi dan perilaku yang cukup kuat.', 'rec' => 'Lakukan asesmen psikologis komprehensif dan program intervensi perilaku.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy', 'min' => 21, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat gangguan emosi dan perilaku yang memerlukan penanganan intensif.', 'rec' => 'Rujuk ke psikiater anak dan psikolog klinis anak, libatkan keluarga dalam program terapi.'],
        ]);
    }

    // ═══════════════════════════════════════════════════
    // DISABILITAS MAJEMUK
    // ═══════════════════════════════════════════════════

    private function seedMDVI(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'mdvi'], [
            'name'                => 'MDVI (Multi-Sensory Impairment)',
            'type'                => 'majemuk',
            'group'               => 'majemuk',
            'description'         => 'Hambatan penglihatan berat yang dikombinasikan dengan hambatan intelektual atau hambatan lain yang signifikan.',
            'icon'                => 'layers',
            'color'               => '#5A4A7A',
            'result_illustration' => 'assets/img/hasil-analisa/Mdvi.jpg',
            'sort_order'          => 90,
            'is_active'           => true,
        ]);

        $domains = [
            [
                'name' => 'Hambatan Penglihatan',
                'desc' => 'Tingkat dan dampak hambatan penglihatan pada anak dengan MDVI.',
                'sort' => 1,
                'questions' => [
                    'Anak memiliki hambatan penglihatan yang berat (low vision atau buta total).',
                    'Anak tidak merespons rangsangan visual bahkan pada jarak sangat dekat.',
                    'Anak membutuhkan media pembelajaran taktil atau auditif sepenuhnya.',
                    'Anak tidak dapat menggunakan indera penglihatan untuk navigasi sehari-hari.',
                    'Anak pernah dikonfirmasi secara medis memiliki gangguan penglihatan berat.',
                    'Anak menggunakan braille atau media auditif dalam kegiatan belajar.',
                    'Anak tidak mengenali objek atau wajah melalui penglihatan bahkan dari jarak dekat.',
                    'Hambatan penglihatan anak ada sejak lahir atau terjadi sejak usia sangat dini.',
                ],
            ],
            [
                'name' => 'Hambatan Penyerta',
                'desc' => 'Hambatan intelektual, komunikasi, atau perkembangan yang menyertai hambatan penglihatan.',
                'sort' => 2,
                'questions' => [
                    'Anak juga memiliki hambatan intelektual yang signifikan selain hambatan penglihatan.',
                    'Anak memiliki keterlambatan perkembangan di lebih dari satu area (motorik, bahasa, kognitif).',
                    'Anak membutuhkan program pendidikan yang sangat individual dan intensif.',
                    'Anak tidak dapat belajar dengan metode reguler meskipun sudah diadaptasi sepenuhnya.',
                    'Anak membutuhkan bantuan penuh dalam semua aspek kegiatan sehari-hari.',
                    'Anak memiliki hambatan komunikasi yang signifikan selain hambatan penglihatan.',
                    'Anak membutuhkan dukungan terapi multipel secara bersamaan.',
                    'Anak sudah ditangani atau dirujuk oleh tim multi-disiplin.',
                ],
            ],
        ];

        foreach ($domains as $d) {
            $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => $d['name']], [
                'description' => $d['desc'], 'sort_order' => $d['sort'], 'is_active' => true,
            ]);
            foreach ($d['questions'] as $i => $q) {
                $questionnaire = Questionnaire::updateOrCreate(
                    ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                    ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
                );
                $this->addYesNoOptions($questionnaire);
            }
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 5,   'color' => '#839986', 'desc' => 'Tidak ada indikasi MDVI yang signifikan.', 'rec' => 'Pantau perkembangan penglihatan dan area perkembangan lainnya.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light', 'min' => 6,  'max' => 13,  'color' => '#8D77AB', 'desc' => 'Terdapat indikator hambatan ganda yang perlu evaluasi lebih lanjut.', 'rec' => 'Konsultasikan dengan tim spesialis — dokter mata dan psikolog perkembangan.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium','min' => 14, 'max' => 21,  'color' => '#A86916', 'desc' => 'Indikasi MDVI yang cukup kuat dan memerlukan penanganan multi-disiplin.', 'rec' => 'Lakukan asesmen komprehensif oleh tim multi-disiplin dan mulai program intervensi dini.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy', 'min' => 22, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat MDVI yang memerlukan penanganan intensif dan spesialistik.', 'rec' => 'Rujuk ke SLB-A atau layanan MDVI khusus, program orientasi mobilitas, dan dukungan keluarga komprehensif.'],
        ]);
    }

    private function seedTunaganda(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'tunaganda'], [
            'name'                => 'Tunaganda',
            'type'                => 'majemuk',
            'group'               => 'majemuk',
            'description'         => 'Kombinasi dua atau lebih jenis hambatan secara bersamaan yang memerlukan penanganan multi-disiplin.',
            'icon'                => 'layers',
            'color'               => '#6A5A7A',
            'result_illustration' => 'assets/img/hasil-analisa/Tunaganda.jpg',
            'sort_order'          => 91,
            'is_active'           => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Kombinasi Hambatan Majemuk'], [
            'description' => 'Indikator adanya kombinasi dua atau lebih jenis hambatan secara bersamaan.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Anak memiliki lebih dari satu jenis hambatan yang teridentifikasi secara bersamaan.',
            'Anak mengalami hambatan fisik dan intelektual secara bersamaan.',
            'Anak mengalami hambatan sensorik dan intelektual secara bersamaan.',
            'Anak membutuhkan program penanganan dari berbagai disiplin ilmu secara bersamaan.',
            'Anak tidak dapat mengikuti program pendidikan yang dirancang hanya untuk satu jenis hambatan.',
            'Anak memiliki kebutuhan komunikasi yang sangat kompleks dan spesifik.',
            'Anak membutuhkan lebih dari satu jenis alat bantu secara bersamaan.',
            'Anak memiliki keterbatasan dalam mobilitas, komunikasi, dan kognisi secara bersamaan.',
            'Anak membutuhkan pengawasan dan pendampingan penuh sepanjang hari di sekolah.',
            'Hambatan yang dimiliki anak saling memperkuat dan memperburuk satu sama lain.',
            'Anak telah mendapatkan penilaian dari lebih dari satu profesional spesialis.',
            'Anak memerlukan lingkungan belajar yang sangat terstruktur dan sangat individual.',
        ];
        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $this->insertRules($cat, null, [
            ['label' => 'Belum Terindikasi', 'severity_level' => 'normal', 'min' => 0,  'max' => 4,   'color' => '#839986', 'desc' => 'Tidak ada indikasi tunaganda yang signifikan.', 'rec' => 'Pantau perkembangan anak secara menyeluruh di berbagai area.'],
            ['label' => 'Perlu Perhatian',   'severity_level' => 'light',  'min' => 5,  'max' => 10,  'color' => '#8D77AB', 'desc' => 'Terdapat indikator kombinasi hambatan yang perlu evaluasi lebih lanjut.', 'rec' => 'Konsultasikan dengan tim spesialis dari berbagai bidang secara bersamaan.'],
            ['label' => 'Terindikasi Sedang','severity_level' => 'medium', 'min' => 11, 'max' => 16,  'color' => '#A86916', 'desc' => 'Indikasi tunaganda yang memerlukan penanganan komprehensif.', 'rec' => 'Lakukan asesmen multi-disiplin dan mulai program intervensi terpadu.'],
            ['label' => 'Terindikasi Kuat',  'severity_level' => 'heavy',  'min' => 17, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat tunaganda yang memerlukan penanganan intensif dan terpadu.', 'rec' => 'Rujuk ke SLB-G atau layanan pendidikan khusus tunaganda dengan dukungan multi-disiplin penuh.'],
        ]);
    }

    // ═══════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════

    private function addYesNoOptions(Questionnaire $q): void
    {
        $options = [
            ['label' => 'Tidak',     'value' => 'never',    'score' => 0, 'sort_order' => 1],
            ['label' => 'Ragu-Ragu', 'value' => 'sometimes','score' => 1, 'sort_order' => 2],
            ['label' => 'Iya',       'value' => 'often',    'score' => 2, 'sort_order' => 3],
        ];
        foreach ($options as $o) {
            AnswerOption::updateOrCreate(
                ['questionnaire_id' => $q->id, 'value' => $o['value']],
                ['label' => $o['label'], 'score' => $o['score'], 'sort_order' => $o['sort_order']]
            );
        }
    }

    private function insertRules(object $cat, ?object $domain, array $rules): void
    {
        foreach ($rules as $r) {
            AssessmentRule::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain?->id ?? null, 'severity_level' => $r['severity_level']],
                [
                    'label'          => $r['label'],
                    'min_score'      => $r['min'],
                    'max_score'      => $r['max'],
                    'description'    => $r['desc'],
                    'recommendation' => $r['rec'],
                    'color'          => $r['color'],
                ]
            );
        }
    }
}
