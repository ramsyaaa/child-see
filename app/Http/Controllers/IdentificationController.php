<?php

namespace App\Http\Controllers;

use App\Models\IdentificationResult;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdentificationController extends Controller
{
    private const DISABILITY_NAMES = [
        'penglihatan' => 'Hambatan Penglihatan',
        'pendengaran' => 'Hambatan Pendengaran',
        'intelektual' => 'Hambatan Intelektual',
    ];

    private const DISABILITY_COLORS = [
        'penglihatan' => '#1E3A5F',
        'pendengaran' => '#8D77AB',
        'intelektual' => '#A86916',
    ];

    private const RECOMMENDATIONS = [
        'penglihatan' => [
            'low'  => 'Tidak ditemukan indikasi kuat hambatan penglihatan. Tetap lakukan pemantauan rutin dan pastikan pemeriksaan mata tahunan.',
            'mid'  => 'Terdapat indikasi awal hambatan penglihatan. Disarankan: (1) pindahkan anak ke baris depan, (2) perbesar ukuran teks bahan ajar, (3) rujuk ke dokter mata dalam 2–4 minggu ke depan.',
            'high' => 'Indikasi kuat hambatan penglihatan. Disarankan segera: (1) konsultasi optometri / dokter mata, (2) koordinasi dengan guru pembimbing khusus, (3) penyesuaian media ajar dengan kontras & ukuran tinggi.',
        ],
        'pendengaran' => [
            'low'  => 'Tidak ditemukan indikasi kuat hambatan pendengaran. Pantau respon anak terhadap instruksi verbal secara berkala.',
            'mid'  => 'Terdapat indikasi awal hambatan pendengaran. Disarankan: (1) berbicara menghadap anak, (2) kurangi kebisingan latar, (3) pemeriksaan audiometri pada 1 bulan ke depan.',
            'high' => 'Indikasi kuat hambatan pendengaran. Disarankan segera: (1) rujukan audiologi, (2) koordinasi dengan terapis wicara, (3) gunakan dukungan visual dan tulisan untuk setiap instruksi penting.',
        ],
        'intelektual' => [
            'low'  => 'Tidak ditemukan indikasi kuat hambatan intelektual. Pertahankan pendekatan pembelajaran yang sudah berjalan.',
            'mid'  => 'Terdapat indikasi awal keterlambatan perkembangan kognitif. Disarankan: (1) asesmen psikologi pendidikan, (2) modifikasi materi dengan langkah-langkah kecil, (3) pelibatan orang tua untuk latihan di rumah.',
            'high' => 'Indikasi kuat hambatan intelektual. Disarankan segera: (1) asesmen psikologi menyeluruh, (2) rujukan ke pusat tumbuh kembang, (3) program pembelajaran individual (PPI) bersama guru pembimbing khusus.',
        ],
    ];

    private function getQuestions(string $type): array
    {
        $dbQuestions = Question::forType($type)->pluck('body')->toArray();
        if (!empty($dbQuestions)) {
            return $dbQuestions;
        }
        // Fallback to hardcoded questions if DB is empty
        $fallback = [
            'penglihatan' => ['Anak sering memicingkan mata saat menatap papan tulis.','Anak terlihat menempelkan buku sangat dekat ke wajah saat membaca.','Anak mengeluhkan sakit kepala setelah membaca atau menulis.','Anak sulit menyalin dari papan tulis meski sudah dipindah ke baris depan.','Anak bertabrakan dengan benda atau ragu saat berjalan di tempat baru.','Anak menghindari aktivitas yang membutuhkan ketelitian visual.','Mata anak tampak berair atau merah tanpa sebab jelas.','Anak sulit mengenali warna dasar yang sama dari jarak sedang.','Anak salah membaca huruf dengan bentuk serupa (b–d, p–q).','Anak lebih nyaman di ruangan yang redup dibanding terang.','Anak meminta bantuan teman untuk membaca instruksi tertulis.','Orang tua melaporkan anak kesulitan menonton dari jarak normal di rumah.'],
            'pendengaran' => ['Anak tidak menoleh saat namanya dipanggil dari belakang.','Anak sering meminta pertanyaan atau instruksi diulang.','Anak berbicara dengan volume yang terlalu keras atau terlalu pelan.','Anak lebih memperhatikan bibir lawan bicara daripada matanya.','Anak sulit mengikuti percakapan dengan beberapa orang sekaligus.','Pelafalan anak kurang jelas untuk kata-kata yang umum pada seusianya.','Anak tampak kaget berlebihan pada suara mendadak.','Anak lebih senang bermain sendiri daripada interaksi verbal.','Anak mengandalkan gerak isyarat teman untuk memahami instruksi.','Anak merespon lebih cepat pada isyarat visual dibanding suara.'],
            'intelektual' => ['Anak memerlukan waktu jauh lebih lama menyelesaikan tugas dibanding teman sebaya.','Anak kesulitan mengingat instruksi 2–3 langkah sekaligus.','Perkembangan bahasa anak terasa tertinggal dari teman sekelasnya.','Anak sulit memahami konsep abstrak seperti waktu atau jumlah.','Anak masih kesulitan mengancing baju, mengikat tali sepatu, atau hal kemandirian lainnya.','Anak kurang mampu memecahkan masalah sederhana secara mandiri.','Anak menghafal mekanis tanpa memahami makna materi.','Anak sulit mengikuti permainan dengan aturan berlapis.','Anak belum lancar membilang secara berurutan hingga 20.','Anak cenderung pasif dan menunggu arahan dalam kelompok.','Anak kesulitan membedakan hal yang penting dari yang tidak penting.','Anak belum dapat menceritakan kembali kejadian sederhana dalam urutan.','Anak membutuhkan pengulangan materi yang sama berhari-hari.','Anak belum menunjukkan minat pada kegiatan sosial kelompok.','Anak tampak kewalahan pada tugas dengan banyak variabel.'],
        ];
        return $fallback[$type] ?? [];
    }

    public function showProfile()
    {
        return view('identification.profile');
    }

    public function storeProfile(Request $request)
    {
        $data = $request->validate([
            'namaPengisi' => 'required|string|max:100',
            'status'      => 'required|in:Guru,Orang Tua',
            'namaAnak'    => 'required|string|max:100',
            'ttl'         => 'required|string|max:100',
            'usia'        => 'required|integer|min:5|max:15',
            'hambatan'    => 'required|string|max:255',
        ]);

        session(['inklu_profile' => $data, 'inklu_consent' => false]);

        return redirect()->route('identification.consent.show');
    }

    public function showConsent()
    {
        if (!session('inklu_profile')) {
            return redirect()->route('identification');
        }
        return view('identification.consent');
    }

    public function storeConsent(Request $request)
    {
        if (!session('inklu_profile')) {
            return redirect()->route('identification');
        }
        $request->validate(['consent' => 'required|accepted']);
        session(['inklu_consent' => true]);
        return redirect()->route('identification.choose');
    }

    public function choose()
    {
        if (!session('inklu_profile') || !session('inklu_consent')) {
            return redirect()->route('identification');
        }
        return view('identification.choose');
    }

    public function showTest(Request $request)
    {
        if (!session('inklu_profile') || !session('inklu_consent')) {
            return redirect()->route('identification');
        }

        $type = $request->input('type');
        if (!array_key_exists($type, self::DISABILITY_NAMES)) {
            return redirect()->route('identification.choose');
        }

        return view('identification.test', ['type' => $type]);
    }

    public function submitTest(Request $request)
    {
        if (!session('inklu_profile') || !session('inklu_consent')) {
            return redirect()->route('identification');
        }

        $type = $request->input('type');
        if (!array_key_exists($type, self::DISABILITY_NAMES)) {
            return redirect()->route('identification.choose');
        }

        $profile = session('inklu_profile');
        $answers = $request->input('answers', []);
        $qs      = $this->getQuestions($type);
        $total   = count($qs);

        $raw = 0;
        foreach ($answers as $v) {
            $raw += $v === 'yes' ? 2 : ($v === 'sometimes' ? 1 : 0);
        }
        $score = $total > 0 ? (int) round($raw / ($total * 2) * 100) : 0;
        $level = $score >= 60 ? 'high' : ($score >= 30 ? 'mid' : 'low');

        $summaryMap = ['high' => 'Indikasi kuat', 'mid' => 'Indikasi awal', 'low' => 'Tidak terindikasi'];

        // Save to DB if authenticated
        $dbResultId = null;
        if (Auth::check()) {
            $dbResult = IdentificationResult::updateOrCreate(
                ['user_id' => Auth::id(), 'type' => $type, 'child_name' => $profile['namaAnak']],
                [
                    'answers'        => $answers,
                    'score'          => $score,
                    'level'          => $level,
                    'child_dob'      => $profile['ttl'] ?? null,
                    'child_age'      => $profile['usia'] ?? null,
                    'filler_name'    => $profile['namaPengisi'],
                    'filler_status'  => $profile['status'],
                ]
            );
            $dbResultId = $dbResult->id;
        }

        $result = [
            'id'             => $dbResultId,
            'type'           => $type,
            'typeName'       => self::DISABILITY_NAMES[$type],
            'color'          => self::DISABILITY_COLORS[$type],
            'date'           => now()->toISOString(),
            'answers'        => $answers,
            'score'          => $score,
            'level'          => $level,
            'summary'        => $summaryMap[$level],
            'recommendation' => self::RECOMMENDATIONS[$type][$level],
        ];

        // Keep latest per type in session
        $tests = session('inklu_tests', []);
        $tests = array_filter($tests, fn($t) => $t['type'] !== $type);
        $tests[] = $result;
        session(['inklu_tests' => array_values($tests)]);

        return redirect()->route('profile.result', ['type' => $type])
            ->with('success', 'Hasil identifikasi tersimpan.');
    }
}
