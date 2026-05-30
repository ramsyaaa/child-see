@extends('home.layout.master')
@section('title', 'Langkah 4 — Tes Identifikasi | InkluSyncID')

@section('content')
@php
$profile = session('inklu_profile');
$disabilityMeta = [
    'penglihatan' => ['name'=>'Hambatan Penglihatan','color'=>'#1E3A5F','icon'=>'<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3" fill="currentColor"/>'],
    'pendengaran' => ['name'=>'Hambatan Pendengaran','color'=>'#8D77AB','icon'=>'<path d="M12 3a7 7 0 0 0-7 7v4a3 3 0 0 0 3 3h1v-8a3 3 0 1 1 6 0c0 2.5-3 3-3 6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>'],
    'intelektual' => ['name'=>'Hambatan Intelektual','color'=>'#A86916','icon'=>'<path d="M9 3a5 5 0 0 0-3 9v3h6v-3a5 5 0 0 0-3-9z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M7 18h4M8 21h2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>'],
];
$questions = [
    'penglihatan' => ['Anak sering memicingkan mata saat menatap papan tulis.','Anak terlihat menempelkan buku sangat dekat ke wajah saat membaca.','Anak mengeluhkan sakit kepala setelah membaca atau menulis.','Anak sulit menyalin dari papan tulis meski sudah dipindah ke baris depan.','Anak bertabrakan dengan benda atau ragu saat berjalan di tempat baru.','Anak menghindari aktivitas yang membutuhkan ketelitian visual.','Mata anak tampak berair atau merah tanpa sebab jelas.','Anak sulit mengenali warna dasar yang sama dari jarak sedang.','Anak salah membaca huruf dengan bentuk serupa (b–d, p–q).','Anak lebih nyaman di ruangan yang redup dibanding terang.','Anak meminta bantuan teman untuk membaca instruksi tertulis.','Orang tua melaporkan anak kesulitan menonton dari jarak normal di rumah.'],
    'pendengaran' => ['Anak tidak menoleh saat namanya dipanggil dari belakang.','Anak sering meminta pertanyaan atau instruksi diulang.','Anak berbicara dengan volume yang terlalu keras atau terlalu pelan.','Anak lebih memperhatikan bibir lawan bicara daripada matanya.','Anak sulit mengikuti percakapan dengan beberapa orang sekaligus.','Pelafalan anak kurang jelas untuk kata-kata yang umum pada seusianya.','Anak tampak kaget berlebihan pada suara mendadak.','Anak lebih senang bermain sendiri daripada interaksi verbal.','Anak mengandalkan gerak isyarat teman untuk memahami instruksi.','Anak merespon lebih cepat pada isyarat visual dibanding suara.'],
    'intelektual' => ['Anak memerlukan waktu jauh lebih lama menyelesaikan tugas dibanding teman sebaya.','Anak kesulitan mengingat instruksi 2–3 langkah sekaligus.','Perkembangan bahasa anak terasa tertinggal dari teman sekelasnya.','Anak sulit memahami konsep abstrak seperti waktu atau jumlah.','Anak masih kesulitan mengancing baju, mengikat tali sepatu, atau hal kemandirian lainnya.','Anak kurang mampu memecahkan masalah sederhana secara mandiri.','Anak menghafal mekanis tanpa memahami makna materi.','Anak sulit mengikuti permainan dengan aturan berlapis.','Anak belum lancar membilang secara berurutan hingga 20.','Anak cenderung pasif dan menunggu arahan dalam kelompok.','Anak kesulitan membedakan hal yang penting dari yang tidak penting.','Anak belum dapat menceritakan kembali kejadian sederhana dalam urutan.','Anak membutuhkan pengulangan materi yang sama berhari-hari.','Anak belum menunjukkan minat pada kegiatan sosial kelompok.','Anak tampak kewalahan pada tugas dengan banyak variabel.'],
];
$disability = $disabilityMeta[$type];
$qs = $questions[$type];
$total = count($qs);
@endphp

<div class="page-fade bg-dots min-h-[60vh]">
    <div class="max-w-5xl mx-auto px-6 lg:px-10 py-12">
        <div class="mb-2 font-mono text-xs uppercase" style="letter-spacing:0.18em;color:#9F86C4;">Start Identification</div>
        <h1 class="font-display text-3xl md:text-4xl leading-tight" style="color:#4A3769;">Langkah 4 — Tes Identifikasi</h1>
        <p class="mt-2 max-w-2xl text-[15px]" style="color:rgba(74,55,105,0.65);">Isi seluruh butir berdasarkan pengamatan Anda selama minimal 2 minggu terakhir.</p>
        @include('identification._steps', ['currentStep' => 4])

        <div class="mt-8">
            <form action="{{ route('identification.test.submit') }}" method="POST" class="card p-7 md:p-9">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="flex items-center gap-4 mb-2">
                    <span class="w-11 h-11 rounded-full grid place-items-center flex-none" style="background:{{ $disability['color'] }}14;color:{{ $disability['color'] }};">
                        <svg viewBox="0 0 24 24" class="w-6 h-6">{!! $disability['icon'] !!}</svg>
                    </span>
                    <div>
                        <div class="font-display text-2xl leading-tight" style="color:#4A3769;">{{ $disability['name'] }}</div>
                        <div class="text-xs mt-0.5" style="color:rgba(74,55,105,0.60);">{{ $total }} butir pernyataan • untuk {{ $profile['namaAnak'] }}</div>
                    </div>
                </div>

                <div class="mt-6 p-4 rounded-lg text-[14px] leading-relaxed" style="border:1px solid rgba(186,166,214,0.30);background:rgba(186,166,214,0.05);color:rgba(74,55,105,0.80);">
                    <strong style="color:#4A3769;">Petunjuk:</strong> pilih <strong>Ya</strong> jika pernyataan terjadi sering/konsisten,
                    <strong>Kadang</strong> jika sesekali, dan <strong>Tidak</strong> jika tidak pernah atau jarang Anda amati.
                </div>

                <div class="mt-3 flex items-center gap-2 text-xs font-mono" style="color:rgba(74,55,105,0.65);">
                    <span id="q-progress">0 / {{ $total }}</span>
                    <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background:#E9E9EB;">
                        <div id="q-bar" class="h-full transition-all" style="width:0%;background:#BAA6D6;"></div>
                    </div>
                </div>

                <div class="mt-4" id="q-list">
                    @foreach($qs as $i => $q)
                    <div class="q-row" data-idx="{{ $i }}">
                        <div class="flex items-start gap-3">
                            <span class="font-mono text-xs mt-0.5 flex-none" style="color:rgba(74,55,105,0.40);">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</span>
                            <span class="text-[15px] leading-relaxed" style="color:#26223A;">{{ $q }}</span>
                        </div>
                        <div class="q-opts">
                            <button type="button" data-val="yes" data-idx="{{ $i }}">Ya</button>
                            <button type="button" data-val="sometimes" data-idx="{{ $i }}">Kadang</button>
                            <button type="button" data-val="no" data-idx="{{ $i }}">Tidak</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div id="answer-inputs"></div>

                <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('identification.choose') }}" class="text-sm" style="color:rgba(74,55,105,0.60);">← Pilih jenis lain</a>
                    <button type="submit" id="submit-test" class="btn-primary px-6 py-3 rounded-full font-semibold" style="opacity:0.4;cursor:not-allowed;" disabled>
                        Lihat Hasil Identifikasi →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const total = {{ $total }};
    const answers = {};
    const bar = document.getElementById('q-bar');
    const prog = document.getElementById('q-progress');
    const submit = document.getElementById('submit-test');
    const inputsDiv = document.getElementById('answer-inputs');

    document.querySelectorAll('.q-opts button').forEach(btn => {
        btn.addEventListener('click', () => {
            const idx = btn.dataset.idx;
            const val = btn.dataset.val;
            answers[idx] = val;

            document.querySelectorAll(`.q-opts button[data-idx="${idx}"]`).forEach(b => b.className = '');
            btn.className = 'sel-' + val;

            const filled = Object.keys(answers).length;
            prog.textContent = `${filled} / ${total}`;
            bar.style.width = (filled / total * 100).toFixed(1) + '%';

            const done = filled === total;
            submit.disabled = !done;
            submit.style.opacity = done ? '1' : '0.4';
            submit.style.cursor = done ? 'pointer' : 'not-allowed';

            inputsDiv.innerHTML = '';
            Object.entries(answers).forEach(([k, v]) => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = `answers[${k}]`;
                inp.value = v;
                inputsDiv.appendChild(inp);
            });
        });
    });
})();
</script>
@endpush
