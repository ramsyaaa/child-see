@extends('home.layout.master')
@section('title', 'Hasil Identifikasi — {{ $result["typeName"] }} | InkluSyncID')

@section('content')
<div class="page-fade">
<div class="bg-dots">
    <div class="max-w-5xl mx-auto px-6 lg:px-10 py-12">
        <a href="{{ route('profile.index') }}" class="inline-flex items-center gap-2 text-sm mb-4" style="color:rgba(74,55,105,0.60);">← Kembali ke profil</a>

        @php
        $disabilityIcons = [
            'penglihatan'=>'<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3" fill="currentColor"/>',
            'pendengaran'=>'<path d="M12 3a7 7 0 0 0-7 7v4a3 3 0 0 0 3 3h1v-8a3 3 0 1 1 6 0c0 2.5-3 3-3 6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
            'intelektual'=>'<path d="M9 3a5 5 0 0 0-3 9v3h6v-3a5 5 0 0 0-3-9z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M7 18h4M8 21h2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        ];
        $otherDisabilities = [
            'penglihatan'=>['name'=>'Hambatan Penglihatan'],
            'pendengaran'=>['name'=>'Hambatan Pendengaran'],
            'intelektual'=>['name'=>'Hambatan Intelektual'],
        ];
        $yesCount = count(array_filter($result['answers'], fn($v) => $v === 'yes'));
        $sometimesCount = count(array_filter($result['answers'], fn($v) => $v === 'sometimes'));
        $noCount = count(array_filter($result['answers'], fn($v) => $v === 'no'));
        @endphp

        <div class="card overflow-hidden">
            {{-- Header --}}
            <div class="p-7 md:p-10" style="background:{{ $result['color'] }};color:#FBF7EF;">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="font-mono text-[11px] uppercase opacity-70" style="letter-spacing:0.18em;">Hasil Identifikasi</div>
                        <h1 class="font-display text-3xl md:text-4xl mt-1 leading-tight">{{ $result['typeName'] }}</h1>
                        <div class="mt-2 text-[14px] opacity-80">{{ $profile['namaAnak'] }} • {{ $profile['usia'] }} tahun • oleh {{ $profile['namaPengisi'] }} ({{ $profile['status'] }})</div>
                    </div>
                    <div class="text-right">
                        <div class="font-mono text-[11px] uppercase opacity-70" style="letter-spacing:0.18em;">Tanggal</div>
                        <div class="font-display text-xl">{{ \Carbon\Carbon::parse($result['date'])->translatedFormat('j F Y') }}</div>
                    </div>
                </div>

                <div class="mt-8 grid md:grid-cols-3 gap-5">
                    <div>
                        <div class="font-mono text-[11px] uppercase opacity-70 mb-1" style="letter-spacing:0.18em;">Skor indikasi</div>
                        <div class="font-display text-5xl">{{ $result['score'] }}<span class="text-2xl opacity-60">/100</span></div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="font-mono text-[11px] uppercase opacity-70 mb-1" style="letter-spacing:0.18em;">Ringkasan</div>
                        <div class="font-display text-2xl leading-tight">{{ $result['summary'] }} hambatan {{ strtolower(str_replace('Hambatan ', '', $result['typeName'])) }} pada anak.</div>
                        <div class="mt-3 h-2 rounded-full overflow-hidden" style="background:rgba(0,0,0,0.20);">
                            <div class="h-full" style="width:{{ $result['score'] }}%;background:rgba(255,255,255,0.90);"></div>
                        </div>
                        <div class="mt-1 flex justify-between text-[10px] font-mono opacity-60">
                            <span>0 — tidak terindikasi</span><span>30 — awal</span><span>60 — kuat</span><span>100</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-7 md:p-10 grid md:grid-cols-12 gap-8">
                <div class="md:col-span-7">
                    <div class="font-mono text-[11px] uppercase mb-2" style="letter-spacing:0.18em;color:#9F86C4;">— Deskripsi Hasil —</div>
                    <h3 class="font-display text-2xl leading-tight" style="color:#4A3769;">Apa artinya hasil ini?</h3>
                    <p class="mt-3 text-[15px] leading-relaxed" style="color:rgba(74,55,105,0.80);">{{ $disabilityShort[$result['type']] }}</p>
                    <p class="mt-3 text-[15px] leading-relaxed" style="color:rgba(74,55,105,0.70);">
                        Berdasarkan {{ count($result['answers']) }} butir pengamatan, InkluSyncID memperhitungkan
                        skor indikasi sebesar <strong>{{ $result['score'] }}/100</strong> dan mengklasifikasikannya
                        sebagai <strong style="color:{{ $result['color'] }};">{{ strtolower($result['summary']) }}</strong>. Hasil ini
                        bukan diagnosis klinis, melainkan petunjuk awal untuk langkah tindak lanjut.
                    </p>

                    <div class="mt-8 font-mono text-[11px] uppercase mb-2" style="letter-spacing:0.18em;color:#9F86C4;">— Rekomendasi —</div>
                    <div class="p-5 rounded-xl" style="background:{{ $result['color'] }}0d;border:1px solid {{ $result['color'] }}33;">
                        <p class="text-[15px] leading-relaxed" style="color:rgba(74,55,105,0.85);">{{ $result['recommendation'] }}</p>
                    </div>

                    <div class="mt-8 grid grid-cols-3 gap-3">
                        <div class="p-4 rounded-lg" style="background:#E9E9EB;">
                            <div class="font-mono text-[10px] uppercase" style="letter-spacing:0.1em;color:#9F86C4;">Ya</div>
                            <div class="font-display text-2xl" style="color:#4A3769;">{{ $yesCount }}</div>
                        </div>
                        <div class="p-4 rounded-lg" style="background:#E9E9EB;">
                            <div class="font-mono text-[10px] uppercase" style="letter-spacing:0.1em;color:#9F86C4;">Kadang</div>
                            <div class="font-display text-2xl" style="color:#4A3769;">{{ $sometimesCount }}</div>
                        </div>
                        <div class="p-4 rounded-lg" style="background:#E9E9EB;">
                            <div class="font-mono text-[10px] uppercase" style="letter-spacing:0.1em;color:#9F86C4;">Tidak</div>
                            <div class="font-display text-2xl" style="color:#4A3769;">{{ $noCount }}</div>
                        </div>
                    </div>
                </div>

                <aside class="md:col-span-5 space-y-4">
                    <div class="card p-5" style="background:#F5F5F6;">
                        <div class="font-mono text-[11px] uppercase mb-2" style="letter-spacing:0.18em;color:#9F86C4;">Jenis Identifikasi</div>
                        <div class="font-display text-lg leading-tight" style="color:#4A3769;">{{ $result['typeName'] }}</div>
                        <div class="text-xs mt-1" style="color:rgba(74,55,105,0.60);">Dari instrumen InkluSyncID terstandar.</div>
                    </div>
                    <div class="card p-5" style="background:#F5F5F6;">
                        <div class="font-mono text-[11px] uppercase mb-2" style="letter-spacing:0.18em;color:#9F86C4;">Konsultasi Lebih Lanjut</div>
                        <div class="font-display text-lg leading-tight" style="color:#4A3769;">WhatsApp Business</div>
                        <p class="text-sm mt-1" style="color:rgba(74,55,105,0.65);">Hubungi tenaga ahli kami untuk tindak lanjut yang sesuai.</p>
                        <a href="#" class="mt-3 btn-primary w-full text-center px-4 py-2.5 rounded-full text-sm font-semibold inline-block">Hubungi via WA</a>
                    </div>
                    <div class="card p-5" style="background:#F5F5F6;">
                        <div class="font-mono text-[11px] uppercase mb-2" style="letter-spacing:0.18em;color:#9F86C4;">Tes lain</div>
                        <div class="space-y-2">
                            @foreach($otherDisabilities as $id => $d)
                            @if($id !== $result['type'])
                            <a href="{{ route('identification.test', ['type' => $id]) }}" class="flex items-center gap-3 p-2 rounded-md hover:bg-[#E9E9EB] transition">
                                <span class="w-7 h-7 rounded-full grid place-items-center" style="background:rgba(74,55,105,0.08);color:#4A3769;">
                                    <svg viewBox="0 0 24 24" class="w-4 h-4">{!! $disabilityIcons[$id] !!}</svg>
                                </span>
                                <span class="text-sm" style="color:#26223A;">{{ $d['name'] }}</span>
                                <span class="ml-auto text-xs" style="color:rgba(74,55,105,0.50);">→</span>
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
