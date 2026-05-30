@extends('home.layout.master')
@section('title', 'Langkah 1 — Pengisian Profil | InkluSyncID')

@section('content')
@php $currentStep = 1; @endphp
<div class="page-fade bg-dots min-h-[60vh]">
    <div class="max-w-5xl mx-auto px-6 lg:px-10 py-12">
        <div class="mb-2 font-mono text-xs uppercase" style="letter-spacing:0.18em;color:#9F86C4;">Start Identification</div>
        <h1 class="font-display text-3xl md:text-4xl leading-tight" style="color:#4A3769;">Langkah 1 — Pengisian Profil</h1>
        <p class="mt-2 max-w-2xl text-[15px]" style="color:rgba(74,55,105,0.65);">Lengkapi data pengisi dan anak terlebih dahulu.</p>
        @include('identification._steps', ['currentStep' => 1])

        <div class="mt-8">
            <form action="{{ route('identification.profile.store') }}" method="POST" class="card p-7 md:p-9">
                @csrf
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="field">
                        <label>Nama Pengisi</label>
                        <input name="namaPengisi" value="{{ old('namaPengisi', session('inklu_profile.namaPengisi')) }}" placeholder="mis. Ibu Astuti" required />
                        @error('namaPengisi')<p class="text-xs mt-1" style="color:#c0392b;">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <div class="grid grid-cols-2 gap-2 mt-0.5">
                            <div class="radio-card-option relative">
                                <input id="st-g" type="radio" name="status" value="Guru" {{ old('status', session('inklu_profile.status')) == 'Guru' ? 'checked' : '' }}>
                                <label for="st-g"><span class="dot"></span>Guru</label>
                            </div>
                            <div class="radio-card-option relative">
                                <input id="st-o" type="radio" name="status" value="Orang Tua" {{ old('status', session('inklu_profile.status')) == 'Orang Tua' ? 'checked' : '' }}>
                                <label for="st-o"><span class="dot"></span>Orang Tua</label>
                            </div>
                        </div>
                        @error('status')<p class="text-xs mt-1" style="color:#c0392b;">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label>Nama Anak</label>
                        <input name="namaAnak" value="{{ old('namaAnak', session('inklu_profile.namaAnak')) }}" placeholder="mis. Raka Ardiansyah" required />
                        @error('namaAnak')<p class="text-xs mt-1" style="color:#c0392b;">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>Tempat, Tanggal Lahir</label>
                        <input name="ttl" value="{{ old('ttl', session('inklu_profile.ttl')) }}" placeholder="mis. Bandung, 12 Mei 2017" required />
                        @error('ttl')<p class="text-xs mt-1" style="color:#c0392b;">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label>Usia (Tahun)</label>
                        <input name="usia" type="number" min="5" max="15" value="{{ old('usia', session('inklu_profile.usia')) }}" placeholder="7" required />
                        @error('usia')<p class="text-xs mt-1" style="color:#c0392b;">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>Hambatan yang Dialami <span class="font-normal" style="color:rgba(74,55,105,0.50);">(gambaran singkat)</span></label>
                        <input name="hambatan" value="{{ old('hambatan', session('inklu_profile.hambatan')) }}" placeholder="mis. sulit menyalin dari papan tulis" required />
                        @error('hambatan')<p class="text-xs mt-1" style="color:#c0392b;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('home') }}" class="text-sm" style="color:rgba(74,55,105,0.60);">← Kembali ke beranda</a>
                    <button type="submit" class="btn-primary px-6 py-3 rounded-full font-semibold">
                        Lanjut — Lembar Persetujuan →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
