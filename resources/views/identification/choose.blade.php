@extends('home.layout.master')
@section('title', 'Langkah 3 — Pilih Jenis Identifikasi | InkluSyncID')

@section('content')
@php $profile = session('inklu_profile'); @endphp
<div class="page-fade bg-dots min-h-[60vh]">
    <div class="max-w-5xl mx-auto px-6 lg:px-10 py-12">
        <div class="mb-2 font-mono text-xs uppercase" style="letter-spacing:0.18em;color:#9F86C4;">Start Identification</div>
        <h1 class="font-display text-3xl md:text-4xl leading-tight" style="color:#4A3769;">Langkah 3 — Pilih Jenis Identifikasi</h1>
        <p class="mt-2 max-w-2xl text-[15px]" style="color:rgba(74,55,105,0.65);">Pilih domain hambatan yang paling mendekati gambaran anak.</p>
        @include('identification._steps', ['currentStep' => 3])

        <div class="mt-8">
            <div class="card p-7 md:p-9">
                <div class="mb-6 p-4 rounded-lg" style="background:#E9E9EB;border:1px solid #D9D9D9;">
                    <div class="font-mono text-[11px] uppercase mb-1" style="letter-spacing:0.18em;color:#9F86C4;">Hambatan yang disampaikan</div>
                    <div class="text-[15px]" style="color:#4A3769;">"{{ $profile['hambatan'] }}"</div>
                </div>
                <p class="text-[15px] leading-relaxed mb-6" style="color:rgba(74,55,105,0.75);">
                    Berdasarkan hambatan yang telah disampaikan, silakan pilih berdasarkan karakteristik yang
                    sesuai dengan gambaran umum hambatan anak. Klik panah untuk melihat deskripsi.
                </p>

                <div class="space-y-3">
                    {{-- Hambatan Penglihatan --}}
                    <details class="disability card p-0 overflow-hidden">
                        <summary class="p-5 md:p-6 flex items-center gap-4">
                            <span class="w-11 h-11 rounded-full grid place-items-center flex-none" style="background:rgba(30,58,95,0.08);color:#1E3A5F;">
                                <svg viewBox="0 0 24 24" class="w-6 h-6"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                            </span>
                            <div class="flex-1">
                                <div class="font-display text-[19px] leading-tight" style="color:#4A3769;">Hambatan Penglihatan</div>
                                <div class="text-xs mt-0.5" style="color:rgba(74,55,105,0.55);">Klik panah ke bawah untuk deskripsi</div>
                            </div>
                            <svg class="chev w-5 h-5" viewBox="0 0 24 24" fill="none" style="color:rgba(74,55,105,0.70);"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </summary>
                        <div class="px-6 pb-6 pt-0" style="border-top:1px solid #E9E9EB;">
                            <div class="grid md:grid-cols-12 gap-6">
                                <div class="md:col-span-8">
                                    <div class="font-mono text-[11px] uppercase mb-2 mt-5" style="letter-spacing:0.18em;color:#9F86C4;">Deskripsi singkat</div>
                                    <p class="text-[15px] leading-relaxed" style="color:rgba(74,55,105,0.80);">Anak dengan hambatan penglihatan menunjukkan kesulitan dalam mengenali huruf/angka dari jarak biasa, sering memicingkan mata, mengeluhkan silau, atau menempelkan wajah ke buku saat membaca.</p>
                                    <p class="text-[14px] leading-relaxed mt-3" style="color:rgba(74,55,105,0.65);">Meliputi low-vision maupun tunanetra total. Tes ini berisi 12 pernyataan observasi selama 1–2 minggu.</p>
                                </div>
                                <div class="md:col-span-4 flex md:items-end md:justify-end pt-4">
                                    <a href="{{ route('identification.test', ['type' => 'penglihatan']) }}" class="btn-accent px-5 py-3 rounded-full font-semibold w-full md:w-auto text-center">Klik Untuk Memulai Tes →</a>
                                </div>
                            </div>
                        </div>
                    </details>

                    {{-- Hambatan Pendengaran --}}
                    <details class="disability card p-0 overflow-hidden">
                        <summary class="p-5 md:p-6 flex items-center gap-4">
                            <span class="w-11 h-11 rounded-full grid place-items-center flex-none" style="background:rgba(141,119,171,0.08);color:#8D77AB;">
                                <svg viewBox="0 0 24 24" class="w-6 h-6"><path d="M12 3a7 7 0 0 0-7 7v4a3 3 0 0 0 3 3h1v-8a3 3 0 1 1 6 0c0 2.5-3 3-3 6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                            </span>
                            <div class="flex-1">
                                <div class="font-display text-[19px] leading-tight" style="color:#4A3769;">Hambatan Pendengaran</div>
                                <div class="text-xs mt-0.5" style="color:rgba(74,55,105,0.55);">Klik panah ke bawah untuk deskripsi</div>
                            </div>
                            <svg class="chev w-5 h-5" viewBox="0 0 24 24" fill="none" style="color:rgba(74,55,105,0.70);"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </summary>
                        <div class="px-6 pb-6 pt-0" style="border-top:1px solid #E9E9EB;">
                            <div class="grid md:grid-cols-12 gap-6">
                                <div class="md:col-span-8">
                                    <div class="font-mono text-[11px] uppercase mb-2 mt-5" style="letter-spacing:0.18em;color:#9F86C4;">Deskripsi singkat</div>
                                    <p class="text-[15px] leading-relaxed" style="color:rgba(74,55,105,0.80);">Anak dengan hambatan pendengaran sering tampak tidak merespon panggilan, meminta pengulangan, berbicara dengan volume tidak lazim, atau lebih mengandalkan isyarat visual dari teman.</p>
                                    <p class="text-[14px] leading-relaxed mt-3" style="color:rgba(74,55,105,0.65);">Meliputi spektrum ringan hingga berat. Tes berisi 10 pernyataan observasi kelas.</p>
                                </div>
                                <div class="md:col-span-4 flex md:items-end md:justify-end pt-4">
                                    <a href="{{ route('identification.test', ['type' => 'pendengaran']) }}" class="btn-accent px-5 py-3 rounded-full font-semibold w-full md:w-auto text-center">Klik Untuk Memulai Tes →</a>
                                </div>
                            </div>
                        </div>
                    </details>

                    {{-- Hambatan Intelektual --}}
                    <details class="disability card p-0 overflow-hidden">
                        <summary class="p-5 md:p-6 flex items-center gap-4">
                            <span class="w-11 h-11 rounded-full grid place-items-center flex-none" style="background:rgba(168,105,22,0.08);color:#A86916;">
                                <svg viewBox="0 0 24 24" class="w-6 h-6"><path d="M9 3a5 5 0 0 0-3 9v3h6v-3a5 5 0 0 0-3-9z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M7 18h4M8 21h2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                            </span>
                            <div class="flex-1">
                                <div class="font-display text-[19px] leading-tight" style="color:#4A3769;">Hambatan Intelektual</div>
                                <div class="text-xs mt-0.5" style="color:rgba(74,55,105,0.55);">Klik panah ke bawah untuk deskripsi</div>
                            </div>
                            <svg class="chev w-5 h-5" viewBox="0 0 24 24" fill="none" style="color:rgba(74,55,105,0.70);"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </summary>
                        <div class="px-6 pb-6 pt-0" style="border-top:1px solid #E9E9EB;">
                            <div class="grid md:grid-cols-12 gap-6">
                                <div class="md:col-span-8">
                                    <div class="font-mono text-[11px] uppercase mb-2 mt-5" style="letter-spacing:0.18em;color:#9F86C4;">Deskripsi singkat</div>
                                    <p class="text-[15px] leading-relaxed" style="color:rgba(74,55,105,0.80);">Anak dengan hambatan intelektual menunjukkan keterlambatan dalam mencapai tonggak perkembangan, kesulitan memahami instruksi berlapis, dan membutuhkan waktu signifikan lebih lama untuk tugas akademik dasar.</p>
                                    <p class="text-[14px] leading-relaxed mt-3" style="color:rgba(74,55,105,0.65);">Identifikasi awal mengacu pada kesenjangan antara kemampuan anak dan rata-rata usia kronologisnya. Tes berisi 15 pernyataan.</p>
                                </div>
                                <div class="md:col-span-4 flex md:items-end md:justify-end pt-4">
                                    <a href="{{ route('identification.test', ['type' => 'intelektual']) }}" class="btn-accent px-5 py-3 rounded-full font-semibold w-full md:w-auto text-center">Klik Untuk Memulai Tes →</a>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>

                <div class="mt-8">
                    <a href="{{ route('identification.consent.show') }}" class="text-sm" style="color:rgba(74,55,105,0.60);">← Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
