@extends('home.layout.master')
@section('title', 'Tentang Child See — Identifikasi ABK Sekolah Dasar')
@section('description', 'Child See adalah platform identifikasi awal Anak Berkebutuhan Khusus berbasis instrumen observasi terstandar untuk guru dan orang tua.')

@section('content')

{{-- Hero --}}
<section class="py-24 relative overflow-hidden" style="background:linear-gradient(135deg,#2E2046 0%,#4A3769 55%,#5C477F 100%);">
    <div class="absolute inset-0 opacity-5" style="background-image:radial-gradient(circle,#BAA6D6 1px,transparent 1px);background-size:32px 32px;"></div>
    <div class="max-w-4xl mx-auto px-6 lg:px-10 text-center relative z-10">
        <p class="text-xs uppercase tracking-widest mb-4 font-mono" style="color:#BAA6D6;">Tentang Kami</p>
        <h1 class="text-4xl md:text-5xl font-display font-bold mb-6" style="color:#F5F5F6;">
            Tentang <em class="not-italic" style="color:#BAA6D6;">Child See</em>
        </h1>
        <p class="text-lg leading-relaxed max-w-2xl mx-auto" style="color:rgba(245,245,246,.75);">
            Child See adalah platform identifikasi awal Anak Berkebutuhan Khusus (ABK) tingkat Sekolah Dasar yang dirancang untuk membantu guru dan orang tua menemukenali hambatan belajar anak sejak dini.
        </p>
    </div>
</section>

{{-- Mission --}}
<section class="py-20" style="background:#F5F5F6;">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-xs uppercase tracking-widest mb-3 font-mono" style="color:#BAA6D6;">Misi Kami</p>
                <h2 class="text-3xl font-display font-bold mb-5" style="color:#2E2046;">Identifikasi Lebih Awal, Penanganan Lebih Tepat</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Banyak anak dengan kebutuhan khusus belum mendapat penanganan yang tepat karena keterlambatan identifikasi. Child See hadir untuk menjembatani kesenjangan itu — memberikan instrumen observasi terstandar yang dapat digunakan oleh siapapun yang peduli pada tumbuh kembang anak.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Dengan pendekatan berbasis bukti dan dikembangkan mengacu pada standar psikologi pendidikan, setiap instrumen dirancang agar dapat digunakan secara mandiri oleh guru kelas maupun orang tua.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @php
                    $cards = [
                        ['icon'=>'fa-clipboard-check','title'=>'Terstandar','desc'=>'Instrumen observasi dikembangkan berbasis literatur psikologi pendidikan.'],
                        ['icon'=>'fa-users','title'=>'Untuk Semua','desc'=>'Dapat digunakan oleh guru, orang tua, maupun tenaga kependidikan.'],
                        ['icon'=>'fa-shield-alt','title'=>'Aman & Privat','desc'=>'Data anak tersimpan aman dan hanya dapat diakses oleh akun terdaftar.'],
                        ['icon'=>'fa-chart-bar','title'=>'Berbasis Data','desc'=>'Hasil asesmen disimpan dan dapat dipantau perkembangannya dari waktu ke waktu.'],
                    ];
                @endphp
                @foreach($cards as $c)
                <div class="rounded-2xl p-5" style="background:#fff;border:1px solid rgba(186,166,214,.2);">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:#F0EEF5;">
                        <i class="fas {{ $c['icon'] }} text-[#5C477F]"></i>
                    </div>
                    <h3 class="font-semibold text-sm mb-1" style="color:#2E2046;">{{ $c['title'] }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ $c['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Categories overview --}}
<section class="py-20" style="background:#fff;">
    <div class="max-w-6xl mx-auto px-6 lg:px-10 text-center">
        <p class="text-xs uppercase tracking-widest mb-3 font-mono" style="color:#BAA6D6;">Cakupan Identifikasi</p>
        <h2 class="text-3xl font-display font-bold mb-4" style="color:#2E2046;">4 Kategori ABK yang Diidentifikasi</h2>
        <p class="text-gray-500 mb-12 max-w-xl mx-auto">Child See mencakup 4 jenis hambatan belajar utama yang umum ditemui pada anak usia sekolah dasar.</p>
        <div class="grid md:grid-cols-4 gap-6">
            @php
                $cats = [
                    ['icon'=>'fa-brain','name'=>'Tunagrahita','color'=>'#A86916','desc'=>'Hambatan intelektual — konseptual, sosial, dan praktis'],
                    ['icon'=>'fa-eye','name'=>'Tunanetra','color'=>'#1E3A5F','desc'=>'Hambatan fungsi penglihatan'],
                    ['icon'=>'fa-book-open','name'=>'Disleksia','color'=>'#5C477F','desc'=>'Kesulitan membaca dan mengeja spesifik'],
                    ['icon'=>'fa-clock','name'=>'Slow Learner','color'=>'#839986','desc'=>'Keterlambatan perkembangan akademik'],
                ];
            @endphp
            @foreach($cats as $cat)
            <div class="rounded-2xl p-6 text-center" style="background:#F5F5F6;border:1px solid rgba(186,166,214,.15);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3" style="background:{{ $cat['color'] }}18;">
                    <i class="fas {{ $cat['icon'] }}" style="color:{{ $cat['color'] }};font-size:1.25rem;"></i>
                </div>
                <h3 class="font-display font-bold mb-2" style="color:#2E2046;">{{ $cat['name'] }}</h3>
                <p class="text-xs text-gray-500">{{ $cat['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16" style="background:linear-gradient(135deg,#2E2046,#4A3769);">
    <div class="max-w-2xl mx-auto text-center px-6">
        <h2 class="text-2xl font-display font-bold mb-3" style="color:#F5F5F6;">Mulai Identifikasi Sekarang</h2>
        <p class="mb-8" style="color:rgba(245,245,246,.65);">Daftar gratis dan mulai lakukan asesmen untuk anak Anda hari ini.</p>
        @auth
            <a href="{{ route('member.assessment.start') }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-2xl font-semibold" style="background:#BAA6D6;color:#2E2046;">
                Mulai Asesmen →
            </a>
        @else
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-2xl font-semibold" style="background:#BAA6D6;color:#2E2046;">
                Daftar Gratis →
            </a>
        @endauth
    </div>
</section>

@endsection
