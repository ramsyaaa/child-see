@extends('home.layout.master')
@section('title', 'Langkah 2 — Persetujuan | InkluSyncID')

@section('content')
@php $profile = session('inklu_profile'); @endphp
<div class="page-fade bg-dots min-h-[60vh]">
    <div class="max-w-5xl mx-auto px-6 lg:px-10 py-12">
        <div class="mb-2 font-mono text-xs uppercase" style="letter-spacing:0.18em;color:#9F86C4;">Start Identification</div>
        <h1 class="font-display text-3xl md:text-4xl leading-tight" style="color:#4A3769;">Langkah 2 — Persetujuan</h1>
        <p class="mt-2 max-w-2xl text-[15px]" style="color:rgba(74,55,105,0.65);">Bacalah dengan saksama sebelum melanjutkan.</p>
        @include('identification._steps', ['currentStep' => 2])

        <div class="mt-8">
            <form action="{{ route('identification.consent.store') }}" method="POST" class="card p-7 md:p-9" id="consent-form">
                @csrf
                <h3 class="font-display text-2xl" style="color:#4A3769;">Lembar Persetujuan Pengguna</h3>
                <p class="text-[15px] leading-relaxed mt-3" style="color:rgba(74,55,105,0.75);">
                    Dengan menekan tombol lanjut, Anda — <strong>{{ $profile['namaPengisi'] }}</strong> sebagai
                    <strong>{{ $profile['status'] }}</strong> — menyatakan bahwa:
                </p>
                <ul class="space-y-2 text-[15px] mt-3" style="color:rgba(74,55,105,0.80);">
                    <li class="flex gap-3"><span class="mt-1.5 w-1.5 h-1.5 rounded-full flex-none inline-block" style="background:#BAA6D6;"></span>Data yang Anda isikan digunakan semata-mata untuk identifikasi awal dan tidak dibagikan ke pihak ketiga tanpa izin.</li>
                    <li class="flex gap-3"><span class="mt-1.5 w-1.5 h-1.5 rounded-full flex-none inline-block" style="background:#BAA6D6;"></span>Hasil identifikasi InkluSyncID bukan diagnosis klinis; keputusan akhir tetap berada pada tenaga profesional.</li>
                    <li class="flex gap-3"><span class="mt-1.5 w-1.5 h-1.5 rounded-full flex-none inline-block" style="background:#BAA6D6;"></span>Anda telah membaca dan memahami instrumen yang akan diberikan pada langkah berikutnya.</li>
                    <li class="flex gap-3"><span class="mt-1.5 w-1.5 h-1.5 rounded-full flex-none inline-block" style="background:#BAA6D6;"></span>Anda memberikan persetujuan atas nama wali sah dari anak bernama <strong>{{ $profile['namaAnak'] }}</strong>.</li>
                </ul>

                <label class="mt-8 flex items-start gap-3 cursor-pointer select-none">
                    <input id="agree" type="checkbox" name="consent" value="1" class="mt-1 w-5 h-5" style="accent-color:#5C477F;" {{ session('inklu_consent') ? 'checked' : '' }} />
                    <span class="text-[15px]" style="color:rgba(74,55,105,0.85);">Saya membaca dan menyetujui pernyataan di atas, serta bersedia mengisi instrumen secara jujur berdasarkan pengamatan saya.</span>
                </label>

                <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('identification') }}" class="text-sm" style="color:rgba(74,55,105,0.60);">← Kembali ke profil</a>
                    <button id="next-btn" type="submit" class="btn-primary px-6 py-3 rounded-full font-semibold" style="opacity:0.4;cursor:not-allowed;" disabled>
                        Lanjut — Memilih Jenis Identifikasi →
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
    const cb = document.getElementById('agree');
    const btn = document.getElementById('next-btn');
    function sync(){
        btn.disabled = !cb.checked;
        btn.style.opacity = cb.checked ? '1' : '0.4';
        btn.style.cursor = cb.checked ? 'pointer' : 'not-allowed';
    }
    cb.addEventListener('change', sync);
    sync();
})();
</script>
@endpush
