@extends('superadmin.layout.master')
@section('page-title', $cmsPage->title . ' — Konten')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.content.index') }}">Konten Halaman</a></li>
            <li class="active">{{ $cmsPage->title }}</li>
        </ul>
        <h2>{{ $cmsPage->title }}</h2>
        <p class="inklu-subtitle">Edit konten seksi dan pengaturan SEO halaman ini</p>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.content.index') }}" class="btn-banner">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    {{-- ── Sections ── --}}
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0" style="color:#4A3769;">
                    <i class="fas fa-layer-group me-2" style="color:#BAA6D6;"></i>Seksi Konten
                </h5>
                <span class="badge" style="background:rgba(92,71,127,.15);color:#5C477F;border:1px solid rgba(92,71,127,.2);">
                    {{ $cmsPage->sections->count() }} seksi
                </span>
            </div>
            <div class="card-body p-0">
                @forelse($cmsPage->sections as $section)
                    <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom"
                         style="border-color:rgba(186,166,214,.2)!important;">
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold" style="color:#2E2046;font-size:0.9rem;">
                                {{ $section->label }}
                            </p>
                            <small class="font-monospace text-muted" style="font-size:0.72rem;">
                                {{ $section->section_key }}
                            </small>
                            @php
                                $preview = collect($section->content)
                                    ->filter(fn($v) => is_string($v) && strlen($v) > 10
                                        && !str_starts_with($v,'http')
                                        && !str_starts_with($v,'cms/')
                                        && !str_starts_with($v,'#'))
                                    ->first();
                            @endphp
                            @if($preview)
                                <p class="mb-0 text-muted mt-1" style="font-size:0.78rem;line-height:1.4;">
                                    "{{ \Str::limit(strip_tags($preview), 80) }}"
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('superadmin.content.section.edit', [$cmsPage, $section]) }}"
                           class="btn btn-sm flex-shrink-0 ms-3"
                           style="background:rgba(92,71,127,.1);color:#5C477F;border:1px solid rgba(92,71,127,.25);">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                    </div>
                @empty
                    <div class="text-center py-5 px-4">
                        <i class="fas fa-folder-open" style="font-size:2rem;color:#BAA6D6;"></i>
                        <p class="text-muted mt-2 mb-1">Belum ada seksi untuk halaman ini.</p>
                        <code class="small">php artisan db:seed --class=CmsSeeder</code>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="text-center d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ route('superadmin.content.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Semua Halaman
            </a>
        </div>
    </div>

    {{-- ── SEO ── --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color:#4A3769;">
                    <i class="fas fa-search me-2" style="color:#BAA6D6;"></i>Pengaturan SEO
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.content.seo', $cmsPage) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:#4A3769;">
                            Judul SEO <span class="text-muted fw-normal">(maks 160 karakter)</span>
                        </label>
                        <input type="text" name="seo_title" maxlength="160"
                               class="form-control @error('seo_title') is-invalid @enderror"
                               value="{{ old('seo_title', $cmsPage->seo_title) }}"
                               id="seo-title-input"
                               placeholder="Judul halaman untuk mesin pencari">
                        @error('seo_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text text-end">
                            <span id="seo-title-len">{{ strlen($cmsPage->seo_title ?? '') }}</span>/160
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:#4A3769;">
                            Meta Deskripsi <span class="text-muted fw-normal">(maks 320)</span>
                        </label>
                        <textarea name="seo_description" maxlength="320" rows="3"
                                  class="form-control @error('seo_description') is-invalid @enderror"
                                  id="seo-desc-input"
                                  placeholder="Ditampilkan di hasil pencarian Google...">{{ old('seo_description', $cmsPage->seo_description) }}</textarea>
                        @error('seo_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:#4A3769;">Kata Kunci</label>
                        <input type="text" name="seo_keywords"
                               class="form-control @error('seo_keywords') is-invalid @enderror"
                               value="{{ old('seo_keywords', $cmsPage->seo_keywords) }}"
                               placeholder="identifikasi ABK, anak berkebutuhan khusus, inklusif">
                        @error('seo_keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold" style="color:#4A3769;">Gambar OG / Media Sosial</label>
                        @if($cmsPage->og_image)
                            <div class="mb-2 rounded overflow-hidden border" style="max-height:120px;">
                                <img src="{{ asset('storage/'.$cmsPage->og_image) }}"
                                     class="w-100 object-fit-cover" alt="OG Image">
                            </div>
                        @endif
                        <input type="file" name="og_image"
                               class="form-control form-control-sm @error('og_image') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">Rekomendasi: 1200×630px JPG/PNG</div>
                        @error('og_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Google SERP Preview --}}
                    <div class="p-3 rounded-3 border mb-4"
                         style="background:#fff;font-family:Arial,sans-serif;border-color:rgba(186,166,214,.3)!important;">
                        <p class="mb-0" style="color:#7B0D1E;font-size:0.7rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;">
                            Pratinjau Google
                        </p>
                        <p class="mb-0 mt-1" id="serp-title"
                           style="color:#1a0dab;font-size:1rem;line-height:1.3;font-weight:500;">
                            {{ $cmsPage->seo_title ?: $cmsPage->title . ' — Child See' }}
                        </p>
                        <p class="mb-0" style="color:#006621;font-size:0.75rem;">
                            Child See.id/{{ $cmsPage->slug }}
                        </p>
                        <p class="mb-0 text-muted" id="serp-desc" style="font-size:0.8rem;line-height:1.4;">
                            {{ $cmsPage->seo_description ?: 'Belum ada deskripsi.' }}
                        </p>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Simpan SEO
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    const titleInput = document.getElementById('seo-title-input');
    const titleLen   = document.getElementById('seo-title-len');
    const serpTitle  = document.getElementById('serp-title');
    const descInput  = document.getElementById('seo-desc-input');
    const serpDesc   = document.getElementById('serp-desc');

    if (titleInput) {
        titleInput.addEventListener('input', () => {
            const l = titleInput.value.length;
            titleLen.textContent = l;
            titleLen.style.color = l > 140 ? '#dc3545' : '';
            serpTitle.textContent = titleInput.value || '{{ addslashes($cmsPage->title) }} — Child See';
        });
    }
    if (descInput) {
        descInput.addEventListener('input', () => {
            serpDesc.textContent = descInput.value || 'Belum ada deskripsi.';
        });
    }
})();
</script>
@endpush
@endsection
