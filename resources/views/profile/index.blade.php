@extends('home.layout.master')
@section('title', 'Profil Anak | Child See')

@section('content')
<div class="page-fade">

@if(!$profile)
{{-- No profile --}}
<div class="max-w-3xl mx-auto px-6 lg:px-10 py-24 text-center">
    <div class="font-mono text-xs uppercase mb-3" style="letter-spacing:0.18em;color:#9F86C4;">Profile</div>
    <h1 class="font-display text-4xl leading-tight" style="color:#4A3769;">Belum ada profil tersimpan.</h1>
    <p class="mt-4 text-[15px] max-w-lg mx-auto" style="color:rgba(74,55,105,0.70);">
        Mulailah identifikasi untuk mengisi profil anak dan menyimpan hasil identifikasinya di sini.
    </p>
    <a href="{{ route('identification') }}" class="inline-block mt-8 btn-primary px-6 py-3 rounded-full font-semibold">Mulai Identifikasi</a>
</div>

@else
<div class="bg-dots">
    <div class="max-w-5xl mx-auto px-6 lg:px-10 py-12">
        <div class="font-mono text-xs uppercase mb-2" style="letter-spacing:0.18em;color:#9F86C4;">Tampilan Profile</div>
        <h1 class="font-display text-3xl md:text-4xl" style="color:#4A3769;">Profil Anak</h1>

        @if(session('success'))
        <div class="mt-4 p-4 rounded-lg text-sm" style="background:rgba(198,217,232,0.20);border:1px solid rgba(168,191,212,0.40);color:#4A3769;">
            {{ session('success') }}
        </div>
        @endif

        <div class="mt-6 grid lg:grid-cols-12 gap-6">
            {{-- Profile card --}}
            <div class="lg:col-span-5 card p-6">
                <div class="aspect-square rounded-xl overflow-hidden mb-5">
                    <div class="ph-stripe h-full"><div class="ph-label">foto anak</div></div>
                </div>
                <dl class="space-y-3 text-[14.5px]">
                    @foreach([['Nama Pengisi',$profile['namaPengisi']],['Status',$profile['status']],['Nama Anak',$profile['namaAnak']],['TTL',$profile['ttl']],['Usia',$profile['usia'].' tahun'],['Hambatan yang Dialami',$profile['hambatan']]] as [$k,$v])
                    <div class="grid gap-3 items-baseline" style="grid-template-columns:2fr 3fr;">
                        <dt class="text-[12.5px] uppercase font-semibold" style="letter-spacing:0.05em;color:rgba(74,55,105,0.55);">{{ $k }}</dt>
                        <dd style="color:#26223A;">{{ $v }}</dd>
                    </div>
                    @endforeach
                </dl>
                <div class="mt-6 flex gap-2">
                    <a href="{{ route('identification') }}" class="btn-ghost px-4 py-2 rounded-full text-sm font-semibold">Ubah profil</a>
                    <form action="{{ route('profile.clear') }}" method="POST" onsubmit="return confirm('Hapus semua data profil dan hasil identifikasi?')">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2 rounded-full text-sm font-semibold" style="border:1px solid #D9D9D9;color:rgba(74,55,105,0.60);">Hapus data</button>
                    </form>
                </div>
            </div>

            {{-- Results --}}
            <div class="lg:col-span-7">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display text-2xl" style="color:#4A3769;">Hasil Identifikasi</h2>
                    <a href="{{ route('identification.choose') }}" class="btn-accent px-4 py-2 rounded-full text-sm font-semibold">+ Identifikasi baru</a>
                </div>

                @if(empty($tests))
                <div class="card p-8 text-center" style="color:rgba(74,55,105,0.60);">
                    Belum ada hasil identifikasi. Silakan mulai dari <a href="{{ route('identification.choose') }}" class="font-semibold" style="color:#9F86C4;">memilih jenis identifikasi</a>.
                </div>
                @else
                @php
                $disabilityIcons = [
                    'penglihatan'=>'<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3" fill="currentColor"/>',
                    'pendengaran'=>'<path d="M12 3a7 7 0 0 0-7 7v4a3 3 0 0 0 3 3h1v-8a3 3 0 1 1 6 0c0 2.5-3 3-3 6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
                    'intelektual'=>'<path d="M9 3a5 5 0 0 0-3 9v3h6v-3a5 5 0 0 0-3-9z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M7 18h4M8 21h2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
                ];
                $disabilityColors = ['penglihatan'=>'#1E3A5F','pendengaran'=>'#8D77AB','intelektual'=>'#A86916'];
                @endphp
                @foreach($tests as $t)
                <article class="card p-5 mb-4">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <span class="w-9 h-9 rounded-full grid place-items-center" style="background:{{ $disabilityColors[$t['type']] }}14;color:{{ $disabilityColors[$t['type']] }};">
                                <svg viewBox="0 0 24 24" class="w-5 h-5">{!! $disabilityIcons[$t['type']] !!}</svg>
                            </span>
                            <div>
                                <div class="font-display text-lg leading-tight" style="color:#4A3769;">{{ $t['typeName'] }}</div>
                                <div class="text-xs font-mono" style="color:rgba(74,55,105,0.55);">{{ \Carbon\Carbon::parse($t['date'])->translatedFormat('j F Y') }}</div>
                            </div>
                        </div>
                        @php
                        $levelStyle = $t['level'] === 'high' ? 'background:#839986;color:#fff;' : ($t['level'] === 'mid' ? 'background:#BAA6D6;color:#fff;' : 'background:#C6D9E8;color:#fff;');
                        @endphp
                        <span class="chip" style="{{ $levelStyle }}">{{ $t['summary'] }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2 rounded-full overflow-hidden" style="background:#E9E9EB;">
                            <div class="h-full" style="width:{{ $t['score'] }}%;background:{{ $disabilityColors[$t['type']] }};"></div>
                        </div>
                        <span class="font-mono text-xs" style="color:rgba(74,55,105,0.65);">{{ $t['score'] }} / 100</span>
                    </div>
                    <a href="{{ route('profile.result', ['type' => $t['type']]) }}" class="mt-4 inline-flex items-center gap-1 font-semibold text-sm" style="color:#9F86C4;">
                        Lihat hasil lengkap →
                    </a>
                </article>
                @endforeach
                @endif

                <div class="mt-6 card p-5 flex items-center gap-4" style="background:rgba(198,217,232,0.08);border-color:rgba(168,191,212,0.30);">
                    <svg class="w-10 h-10 flex-none" viewBox="0 0 24 24" fill="none" style="color:#A8BFD4;"><path d="M20.5 3.5A11 11 0 0 0 3.5 20.5L2 22l1.5-1.5" stroke="currentColor" stroke-width="1.6"/><path d="M8 11s1 2 4 2 4-2 4-2M9 9h.01M15 9h.01" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                    <div class="flex-1">
                        <div class="font-display text-lg leading-tight" style="color:#4A3769;">Konsultasi lebih lanjut</div>
                        <div class="text-sm" style="color:rgba(74,55,105,0.65);">Diskusikan hasil dengan tenaga ahli kami via WhatsApp Business.</div>
                    </div>
                    <a href="#" class="btn-primary px-4 py-2 rounded-full text-sm font-semibold">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

</div>
@endsection
