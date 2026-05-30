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
        $this->seedTunagrahita();
        $this->seedTunanetra();
        $this->seedDisleksia();
        $this->seedSlowLearner();
    }

    // ─────────────────────────────────────────────────
    // TUNAGRAHITA
    // ─────────────────────────────────────────────────
    private function seedTunagrahita(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'tunagrahita'], [
            'name'        => 'Tunagrahita',
            'type'        => 'intelektual',
            'description' => 'Hambatan intelektual yang mempengaruhi kemampuan kognitif, sosial, dan adaptif anak.',
            'icon'        => 'brain',
            'color'       => '#A86916',
            'sort_order'  => 1,
            'is_active'   => true,
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

        // Overall rules for tunagrahita
        $rules = [
            ['label' => 'Belum Terindikasi',     'severity_level' => 'normal', 'min' => 0,  'max' => 19,  'color' => '#839986', 'desc' => 'Tidak ada indikasi tunagrahita yang signifikan.', 'rec' => 'Tetap pantau perkembangan anak secara berkala.'],
            ['label' => 'Terindikasi Ringan',     'severity_level' => 'light',  'min' => 20, 'max' => 29,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa indikasi hambatan intelektual ringan.', 'rec' => 'Konsultasikan dengan psikolog anak untuk asesmen lebih lanjut.'],
            ['label' => 'Terindikasi Sedang',     'severity_level' => 'medium', 'min' => 30, 'max' => 38,  'color' => '#A86916', 'desc' => 'Terdapat indikasi hambatan intelektual yang perlu penanganan.', 'rec' => 'Segera lakukan asesmen psikologis dan pertimbangkan program pendidikan khusus.'],
            ['label' => 'Terindikasi Kuat',       'severity_level' => 'heavy',  'min' => 39, 'max' => 999, 'color' => '#dc3545', 'desc' => 'Indikasi kuat adanya hambatan intelektual berat.', 'rec' => 'Rujuk ke psikolog/psikiater anak dan daftarkan ke sekolah luar biasa (SLB).'],
        ];
        $this->insertRules($cat, null, $rules);
    }

    // ─────────────────────────────────────────────────
    // TUNANETRA
    // ─────────────────────────────────────────────────
    private function seedTunanetra(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'tunanetra'], [
            'name'        => 'Tunanetra',
            'type'        => 'sensorik',
            'description' => 'Hambatan penglihatan yang mempengaruhi kemampuan visual anak.',
            'icon'        => 'eye',
            'color'       => '#1E3A5F',
            'sort_order'  => 2,
            'is_active'   => true,
        ]);

        $domain = Domain::updateOrCreate(['category_id' => $cat->id, 'name' => 'Fungsi Penglihatan'], [
            'description' => 'Kemampuan dan keterbatasan fungsi penglihatan anak.', 'sort_order' => 1, 'is_active' => true,
        ]);

        $questions = [
            'Anak sering memicingkan mata saat melihat benda atau tulisan.',
            'Anak mendekatkan wajah ke buku atau papan tulis saat membaca.',
            'Anak sering mengeluh sakit kepala atau mata perih setelah membaca.',
            'Anak kesulitan melihat tulisan di papan tulis dari jarak normal.',
            'Anak sering menabrak benda atau tersandung saat berjalan.',
            'Anak kesulitan membedakan warna tertentu.',
            'Anak tidak dapat mengenali wajah orang dari jarak jauh.',
            'Anak menghindari aktivitas yang membutuhkan penglihatan detail.',
            'Anak sering menggosok-gosok mata.',
            'Anak memiliki mata yang terlihat tidak sejajar (juling).',
            'Anak kesulitan membaca tulisan berukuran normal.',
            'Anak membutuhkan cahaya sangat terang untuk melihat dengan jelas.',
        ];

        foreach ($questions as $i => $q) {
            $questionnaire = Questionnaire::updateOrCreate(
                ['category_id' => $cat->id, 'domain_id' => $domain->id, 'question' => $q],
                ['question_type' => 'yes_no', 'weight' => 1, 'sort_order' => $i + 1, 'is_active' => true]
            );
            $this->addYesNoOptions($questionnaire);
        }

        $rules = [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0,  'max' => 3,  'color' => '#839986', 'desc' => 'Tidak ada indikasi hambatan penglihatan signifikan.', 'rec' => 'Tetap lakukan pemeriksaan mata rutin setahun sekali.'],
            ['label' => 'Perlu Perhatian',    'severity_level' => 'light',  'min' => 4,  'max' => 7,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda hambatan penglihatan.', 'rec' => 'Periksakan ke dokter mata untuk pemeriksaan lebih lanjut.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 8,  'max' => 10, 'color' => '#A86916', 'desc' => 'Indikasi hambatan penglihatan yang perlu penanganan segera.', 'rec' => 'Segera periksakan ke dokter mata spesialis dan pertimbangkan alat bantu.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 11, 'max' => 999,'color' => '#dc3545', 'desc' => 'Indikasi kuat hambatan penglihatan berat (low vision/totally blind).', 'rec' => 'Rujuk ke dokter mata dan sekolah luar biasa bagian A (SLBA).'],
        ];
        $this->insertRules($cat, null, $rules);
    }

    // ─────────────────────────────────────────────────
    // DISLEKSIA
    // ─────────────────────────────────────────────────
    private function seedDisleksia(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'disleksia'], [
            'name'        => 'Disleksia',
            'type'        => 'akademik',
            'description' => 'Kesulitan belajar spesifik dalam membaca dan mengeja yang tidak berkaitan dengan kecerdasan.',
            'icon'        => 'book',
            'color'       => '#5C477F',
            'sort_order'  => 3,
            'is_active'   => true,
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

        $rules = [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0, 'max' => 2,  'color' => '#839986', 'desc' => 'Tidak ada indikasi disleksia yang signifikan.', 'rec' => 'Tetap pantau kemampuan membaca dan menulis anak.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light',  'min' => 3, 'max' => 5,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda disleksia ringan.', 'rec' => 'Konsultasikan dengan guru dan pertimbangkan terapi membaca.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 6, 'max' => 8,  'color' => '#A86916', 'desc' => 'Indikasi disleksia yang perlu intervensi khusus.', 'rec' => 'Lakukan asesmen psikologis dan ikutsertakan dalam program remedial membaca.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 9, 'max' => 999,'color' => '#dc3545', 'desc' => 'Indikasi kuat disleksia berat.', 'rec' => 'Segera rujuk ke psikolog pendidikan dan sekolah dengan program inklusi.'],
        ];
        $this->insertRules($cat, null, $rules);
    }

    // ─────────────────────────────────────────────────
    // SLOW LEARNER
    // ─────────────────────────────────────────────────
    private function seedSlowLearner(): void
    {
        $cat = Category::updateOrCreate(['slug' => 'slow-learner'], [
            'name'        => 'Slow Learner',
            'type'        => 'akademik',
            'description' => 'Anak yang memiliki kemampuan akademik di bawah rata-rata namun tidak termasuk tunagrahita.',
            'icon'        => 'clock',
            'color'       => '#839986',
            'sort_order'  => 4,
            'is_active'   => true,
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

        $rules = [
            ['label' => 'Belum Terindikasi',  'severity_level' => 'normal', 'min' => 0, 'max' => 2,  'color' => '#839986', 'desc' => 'Tidak ada indikasi slow learner yang signifikan.', 'rec' => 'Tetap berikan dukungan dan motivasi dalam belajar.'],
            ['label' => 'Terindikasi Ringan', 'severity_level' => 'light',  'min' => 3, 'max' => 5,  'color' => '#8D77AB', 'desc' => 'Terdapat beberapa tanda keterlambatan belajar.', 'rec' => 'Berikan bimbingan belajar tambahan dan strategi belajar yang lebih visual.'],
            ['label' => 'Terindikasi Sedang', 'severity_level' => 'medium', 'min' => 6, 'max' => 8,  'color' => '#A86916', 'desc' => 'Indikasi slow learner yang memerlukan intervensi.', 'rec' => 'Konsultasikan dengan psikolog pendidikan dan buat rencana belajar individual.'],
            ['label' => 'Terindikasi Kuat',   'severity_level' => 'heavy',  'min' => 9, 'max' => 999,'color' => '#dc3545', 'desc' => 'Indikasi kuat slow learner yang memerlukan perhatian serius.', 'rec' => 'Lakukan asesmen menyeluruh dan pertimbangkan program pendidikan inklusif.'],
        ];
        $this->insertRules($cat, null, $rules);
    }

    // ─────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────
    private function addYesNoOptions(Questionnaire $q): void {
        $options = [
            ['label' => 'Tidak Pernah', 'value' => 'never',     'score' => 0, 'sort_order' => 1],
            ['label' => 'Kadang-Kadang','value' => 'sometimes',  'score' => 1, 'sort_order' => 2],
            ['label' => 'Sering',       'value' => 'often',      'score' => 2, 'sort_order' => 3],
        ];
        foreach ($options as $o) {
            AnswerOption::updateOrCreate(
                ['questionnaire_id' => $q->id, 'value' => $o['value']],
                ['label' => $o['label'], 'score' => $o['score'], 'sort_order' => $o['sort_order']]
            );
        }
    }

    private function insertRules(object $cat, ?object $domain, array $rules): void {
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
