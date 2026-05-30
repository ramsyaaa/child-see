<?php

namespace App\Http\Controllers;

use App\Models\IdentificationResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private const DISABILITY_SHORT = [
        'penglihatan' => 'Anak dengan hambatan penglihatan menunjukkan kesulitan dalam mengenali huruf/angka dari jarak biasa, sering memicingkan mata, mengeluhkan silau, atau menempelkan wajah ke buku saat membaca.',
        'pendengaran' => 'Anak dengan hambatan pendengaran sering tampak tidak merespon panggilan, meminta pengulangan, berbicara dengan volume tidak lazim, atau lebih mengandalkan isyarat visual dari teman.',
        'intelektual' => 'Anak dengan hambatan intelektual menunjukkan keterlambatan dalam mencapai tonggak perkembangan, kesulitan memahami instruksi berlapis, dan membutuhkan waktu signifikan lebih lama untuk tugas akademik dasar.',
    ];

    public function index()
    {
        $profile = session('inklu_profile');

        if (Auth::check()) {
            $dbResults = IdentificationResult::where('user_id', Auth::id())
                ->orderByDesc('updated_at')
                ->get();

            $tests = $dbResults->map(fn($r) => $this->resultToArray($r))->toArray();
        } else {
            $tests = session('inklu_tests', []);
            usort($tests, fn($a, $b) => strcmp($b['date'], $a['date']));
        }

        return view('profile.index', compact('profile', 'tests'));
    }

    public function result(Request $request)
    {
        $profile = session('inklu_profile');
        $type    = $request->input('type');

        if (Auth::check()) {
            $dbResult = IdentificationResult::where('user_id', Auth::id())
                ->where('type', $type)
                ->latest('updated_at')
                ->first();

            if (!$dbResult) {
                return redirect()->route('profile.index');
            }

            // Sync profile from DB result if session is missing
            if (!$profile) {
                $profile = [
                    'namaPengisi' => $dbResult->filler_name,
                    'status'      => $dbResult->filler_status,
                    'namaAnak'    => $dbResult->child_name,
                    'ttl'         => $dbResult->child_dob,
                    'usia'        => $dbResult->child_age,
                    'hambatan'    => '',
                ];
            }

            $result = $this->resultToArray($dbResult);
        } else {
            $tests  = session('inklu_tests', []);
            $result = collect($tests)->firstWhere('type', $type);

            if (!$result || !$profile) {
                return redirect()->route('profile.index');
            }
        }

        $disabilityShort = self::DISABILITY_SHORT;

        return view('profile.result', compact('profile', 'result', 'disabilityShort'));
    }

    public function clear()
    {
        session()->forget(['inklu_profile', 'inklu_consent', 'inklu_tests']);
        return redirect()->route('profile.index')->with('success', 'Data sesi profil telah dihapus.');
    }

    private function resultToArray(IdentificationResult $r): array
    {
        $recsMap = [
            'penglihatan' => ['low' => 'Tidak ditemukan indikasi kuat hambatan penglihatan. Tetap lakukan pemantauan rutin dan pastikan pemeriksaan mata tahunan.','mid' => 'Terdapat indikasi awal hambatan penglihatan. Disarankan: (1) pindahkan anak ke baris depan, (2) perbesar ukuran teks bahan ajar, (3) rujuk ke dokter mata dalam 2–4 minggu ke depan.','high' => 'Indikasi kuat hambatan penglihatan. Disarankan segera: (1) konsultasi optometri / dokter mata, (2) koordinasi dengan guru pembimbing khusus, (3) penyesuaian media ajar dengan kontras & ukuran tinggi.'],
            'pendengaran' => ['low' => 'Tidak ditemukan indikasi kuat hambatan pendengaran. Pantau respon anak terhadap instruksi verbal secara berkala.','mid' => 'Terdapat indikasi awal hambatan pendengaran. Disarankan: (1) berbicara menghadap anak, (2) kurangi kebisingan latar, (3) pemeriksaan audiometri pada 1 bulan ke depan.','high' => 'Indikasi kuat hambatan pendengaran. Disarankan segera: (1) rujukan audiologi, (2) koordinasi dengan terapis wicara, (3) gunakan dukungan visual dan tulisan untuk setiap instruksi penting.'],
            'intelektual' => ['low' => 'Tidak ditemukan indikasi kuat hambatan intelektual. Pertahankan pendekatan pembelajaran yang sudah berjalan.','mid' => 'Terdapat indikasi awal keterlambatan perkembangan kognitif. Disarankan: (1) asesmen psikologi pendidikan, (2) modifikasi materi dengan langkah-langkah kecil, (3) pelibatan orang tua untuk latihan di rumah.','high' => 'Indikasi kuat hambatan intelektual. Disarankan segera: (1) asesmen psikologi menyeluruh, (2) rujukan ke pusat tumbuh kembang, (3) program pembelajaran individual (PPI) bersama guru pembimbing khusus.'],
        ];
        $summaryMap = ['high' => 'Indikasi kuat', 'mid' => 'Indikasi awal', 'low' => 'Tidak terindikasi'];
        $nameMap    = ['penglihatan' => 'Hambatan Penglihatan', 'pendengaran' => 'Hambatan Pendengaran', 'intelektual' => 'Hambatan Intelektual'];
        $colorMap   = ['penglihatan' => '#1E3A5F', 'pendengaran' => '#8D77AB', 'intelektual' => '#A86916'];

        return [
            'id'             => $r->id,
            'type'           => $r->type,
            'typeName'       => $nameMap[$r->type] ?? ucfirst($r->type),
            'color'          => $colorMap[$r->type] ?? '#5C477F',
            'date'           => $r->updated_at->toISOString(),
            'answers'        => $r->answers ?? [],
            'score'          => $r->score,
            'level'          => $r->level,
            'summary'        => $summaryMap[$r->level] ?? '',
            'recommendation' => $recsMap[$r->type][$r->level] ?? '',
        ];
    }
}
