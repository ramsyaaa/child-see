<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        Question::truncate();

        $questions = [
            'penglihatan' => [
                'Anak sering memicingkan mata saat menatap papan tulis.',
                'Anak terlihat menempelkan buku sangat dekat ke wajah saat membaca.',
                'Anak mengeluhkan sakit kepala setelah membaca atau menulis.',
                'Anak sulit menyalin dari papan tulis meski sudah dipindah ke baris depan.',
                'Anak bertabrakan dengan benda atau ragu saat berjalan di tempat baru.',
                'Anak menghindari aktivitas yang membutuhkan ketelitian visual.',
                'Mata anak tampak berair atau merah tanpa sebab jelas.',
                'Anak sulit mengenali warna dasar yang sama dari jarak sedang.',
                'Anak salah membaca huruf dengan bentuk serupa (b–d, p–q).',
                'Anak lebih nyaman di ruangan yang redup dibanding terang.',
                'Anak meminta bantuan teman untuk membaca instruksi tertulis.',
                'Orang tua melaporkan anak kesulitan menonton dari jarak normal di rumah.',
            ],
            'pendengaran' => [
                'Anak tidak menoleh saat namanya dipanggil dari belakang.',
                'Anak sering meminta pertanyaan atau instruksi diulang.',
                'Anak berbicara dengan volume yang terlalu keras atau terlalu pelan.',
                'Anak lebih memperhatikan bibir lawan bicara daripada matanya.',
                'Anak sulit mengikuti percakapan dengan beberapa orang sekaligus.',
                'Pelafalan anak kurang jelas untuk kata-kata yang umum pada seusianya.',
                'Anak tampak kaget berlebihan pada suara mendadak.',
                'Anak lebih senang bermain sendiri daripada interaksi verbal.',
                'Anak mengandalkan gerak isyarat teman untuk memahami instruksi.',
                'Anak merespon lebih cepat pada isyarat visual dibanding suara.',
            ],
            'intelektual' => [
                'Anak memerlukan waktu jauh lebih lama menyelesaikan tugas dibanding teman sebaya.',
                'Anak kesulitan mengingat instruksi 2–3 langkah sekaligus.',
                'Perkembangan bahasa anak terasa tertinggal dari teman sekelasnya.',
                'Anak sulit memahami konsep abstrak seperti waktu atau jumlah.',
                'Anak masih kesulitan mengancing baju, mengikat tali sepatu, atau hal kemandirian lainnya.',
                'Anak kurang mampu memecahkan masalah sederhana secara mandiri.',
                'Anak menghafal mekanis tanpa memahami makna materi.',
                'Anak sulit mengikuti permainan dengan aturan berlapis.',
                'Anak belum lancar membilang secara berurutan hingga 20.',
                'Anak cenderung pasif dan menunggu arahan dalam kelompok.',
                'Anak kesulitan membedakan hal yang penting dari yang tidak penting.',
                'Anak belum dapat menceritakan kembali kejadian sederhana dalam urutan.',
                'Anak membutuhkan pengulangan materi yang sama berhari-hari.',
                'Anak belum menunjukkan minat pada kegiatan sosial kelompok.',
                'Anak tampak kewalahan pada tugas dengan banyak variabel.',
            ],
        ];

        foreach ($questions as $type => $bodies) {
            foreach ($bodies as $i => $body) {
                Question::create(['type' => $type, 'body' => $body, 'sort_order' => $i + 1, 'active' => true]);
            }
        }
    }
}
