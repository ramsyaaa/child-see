@extends('home.layout.master')

@section('title', ($site['seo_title'] ?? '') ?: 'Child See — Identifikasi Anak Berkebutuhan Khusus')
@section('description', ($site['seo_description'] ?? '') ?: 'Child See membantu guru dan orang tua mengidentifikasi hambatan belajar anak SD secara dini.')

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden py-24 md:py-36" style="background:linear-gradient(135deg,#2E2046 0%,#4A3769 55%,#5C477F 100%);">
    <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full opacity-10" style="background:radial-gradient(circle,#BAA6D6,transparent 70%);"></div>
    <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full opacity-10" style="background:radial-gradient(circle,#BAA6D6,transparent 70%);"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-10 relative z-10">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full mb-6" style="background:rgba(186,166,214,.15);border:1px solid rgba(186,166,214,.25);">
                <span class="w-2 h-2 rounded-full" style="background:#BAA6D6;"></span>
                <span class="text-xs uppercase tracking-widest" style="color:#BAA6D6;">Identifikasi ABK Terstandar</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-display font-bold mb-6 leading-tight" style="color:#F5F5F6;">
                Kenali Potensi<br>
                <em class="not-italic" style="color:#BAA6D6;">Setiap Anak</em><br>
                Lebih Awal
            </h1>
            <p class="text-lg md:text-xl mb-8 leading-relaxed" style="color:rgba(245,245,246,0.7);max-width:560px;">
                Child See membantu guru dan orang tua mengidentifikasi hambatan belajar anak SD secara dini dengan instrumen observasi terstandar dan berbasis bukti.
            </p>
            <div class="flex flex-wrap gap-4">
                @auth
                    <a href="{{ route('member.assessment.start') }}" class="inline-flex items-center gap-3 px-7 py-4 rounded-2xl font-semibold transition-transform hover:-translate-y-0.5" style="background:#BAA6D6;color:#2E2046;">
                        Mulai Asesmen Sekarang
                    </a>
                    <a href="{{ route('member.dashboard') }}" class="inline-flex items-center gap-2 px-7 py-4 rounded-2xl font-semibold" style="border:1px solid rgba(186,166,214,.35);color:rgba(245,245,246,.85);">
                        Dashboard Saya
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-7 py-4 rounded-2xl font-semibold transition-transform hover:-translate-y-0.5" style="background:#BAA6D6;color:#2E2046;">
                        Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-7 py-4 rounded-2xl font-semibold" style="border:1px solid rgba(186,166,214,.35);color:rgba(245,245,246,.85);">
                        Sudah Punya Akun
                    </a>
                @endauth
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6 mt-16 max-w-lg">
            <div class="text-center">
                <div class="text-3xl font-display font-bold" style="color:#BAA6D6;">4</div>
                <div class="text-xs mt-1 uppercase tracking-wider" style="color:rgba(245,245,246,.5);">Kategori ABK</div>
            </div>
            <div class="text-center" style="border-left:1px solid rgba(186,166,214,.2);border-right:1px solid rgba(186,166,214,.2);">
                <div class="text-3xl font-display font-bold" style="color:#BAA6D6;">49+</div>
                <div class="text-xs mt-1 uppercase tracking-wider" style="color:rgba(245,245,246,.5);">Indikator</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-display font-bold" style="color:#BAA6D6;">100%</div>
                <div class="text-xs mt-1 uppercase tracking-wider" style="color:rgba(245,245,246,.5);">Berbasis Bukti</div>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section class="py-20" style="background:#F5F5F6;">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="text-center mb-14">
            <p class="text-xs uppercase tracking-widest mb-3 font-mono" style="color:#BAA6D6;">Kategori Identifikasi</p>
            <h2 class="text-3xl md:text-4xl font-display font-bold" style="color:#2E2046;">Hambatan yang Dapat Diidentifikasi</h2>
            <p class="mt-4 max-w-xl mx-auto" style="color:rgba(38,34,58,.6);">
                Instrumen observasi terstandar untuk 4 jenis hambatan belajar utama pada anak usia sekolah dasar.
            </p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($categories as $cat)
            <div class="rounded-2xl p-6 border transition-shadow hover:shadow-lg" style="background:#fff;border-color:rgba(186,166,214,.2);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background:{{ $cat->color }}22;">
                    @php
                        $svgPaths = [
                            'brain' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z',
                            'eye'   => 'M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z M12 9a3 3 0 100 6 3 3 0 000-6z',
                            'book'  => 'M4 19.5A2.5 2.5 0 016.5 17H20 M4 19.5A2.5 2.5 0 014 17V4h16v13H6.5a2.5 2.5 0 00-2.5 2.5z',
                            'clock' => 'M12 2a10 10 0 100 20A10 10 0 0012 2zm0 5v5h5',
                        ];
                    @endphp
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="{{ $cat->color }}" stroke-width="1.8" stroke-linecap="round">
                        <path d="{{ $svgPaths[$cat->icon] ?? $svgPaths['book'] }}"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold mb-2" style="color:#2E2046;">{{ $cat->name }}</h3>
                <p class="text-sm leading-relaxed" style="color:rgba(38,34,58,.6);">{{ $cat->description }}</p>
                <div class="mt-4">
                    <span class="text-xs font-mono px-2 py-1 rounded" style="background:{{ $cat->color }}18;color:{{ $cat->color }};">{{ $cat->type }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-12 text-gray-400">
                Belum ada kategori tersedia.
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="py-20" style="background:#fff;">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="text-center mb-14">
            <p class="text-xs uppercase tracking-widest mb-3 font-mono" style="color:#BAA6D6;">Cara Kerja</p>
            <h2 class="text-3xl md:text-4xl font-display font-bold" style="color:#2E2046;">Hanya 4 Langkah Mudah</h2>
        </div>
        <div class="grid md:grid-cols-4 gap-8">
            @php
                $steps = [
                    ['n'=>'01','title'=>'Buat Akun','desc'=>'Daftar gratis menggunakan email. Tidak diperlukan kartu kredit.'],
                    ['n'=>'02','title'=>'Tambahkan Data Anak','desc'=>'Masukkan profil anak yang ingin diidentifikasi hambatan belajarnya.'],
                    ['n'=>'03','title'=>'Isi Kuesioner','desc'=>'Jawab pertanyaan observasi sesuai perilaku anak yang Anda amati sehari-hari.'],
                    ['n'=>'04','title'=>'Lihat Hasil','desc'=>'Dapatkan laporan otomatis dengan tingkat indikasi dan rekomendasi tindak lanjut.'],
                ];
            @endphp
            @foreach($steps as $step)
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl mx-auto mb-4" style="background:linear-gradient(135deg,#4A3769,#5C477F);">
                    <span class="font-display text-xl font-bold text-white">{{ $step['n'] }}</span>
                </div>
                <h3 class="font-display font-bold mb-2" style="color:#2E2046;">{{ $step['title'] }}</h3>
                <p class="text-sm" style="color:rgba(38,34,58,.6);">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20" style="background:linear-gradient(135deg,#2E2046 0%,#4A3769 100%);">
    <div class="max-w-3xl mx-auto text-center px-6">
        <h2 class="text-3xl md:text-4xl font-display font-bold mb-4" style="color:#F5F5F6;">
            Mulai Identifikasi Sekarang,<br><em class="not-italic" style="color:#BAA6D6;">Gratis</em>
        </h2>
        <p class="mb-10" style="color:rgba(245,245,246,.65);">
            49+ indikator observasi terstandar membantu Anda mengenali kebutuhan khusus anak lebih awal dan mengambil langkah yang tepat.
        </p>
        @auth
            <a href="{{ route('member.assessment.start') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl font-semibold text-lg" style="background:#BAA6D6;color:#2E2046;">
                Mulai Asesmen →
            </a>
        @else
            <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl font-semibold text-lg" style="background:#BAA6D6;color:#2E2046;">
                Daftar Gratis Sekarang →
            </a>
        @endauth
    </div>
</section>

@endsection
