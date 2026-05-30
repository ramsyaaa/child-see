@extends('superadmin.layout.master')
@section('page-title', 'Edit: ' . $section->label)

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.content.index') }}">Konten Halaman</a></li>
            <li><a href="{{ route('superadmin.content.show', $cmsPage) }}">{{ $cmsPage->title }}</a></li>
            <li class="active">{{ $section->label }}</li>
        </ul>
        <h2>Edit: {{ $section->label }}</h2>
        <p class="inklu-subtitle">Halaman: {{ $cmsPage->title }}</p>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.content.show', $cmsPage) }}" class="btn-banner">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4">
        @foreach($errors->all() as $e)
            <div><i class="fas fa-exclamation-circle me-1"></i>{{ $e }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-3">
                <div>
                    <h5 class="mb-0" style="color:#4A3769;">{{ $section->label }}</h5>
                    <small class="font-monospace text-muted" style="font-size:0.7rem;">{{ $section->section_key }}</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.content.section.update', [$cmsPage, $section]) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    @php
                        $content = $section->content ?? [];
                    @endphp

                    @foreach($content as $key => $value)
                        @php
                            $isImage    = str_contains($key,'image') || str_contains($key,'img') || str_contains($key,'photo');
                            $isLongText = str_contains($key,'body') || str_contains($key,'_a') || str_contains($key,'description');
                            $isHeading  = str_contains($key,'heading') || str_contains($key,'title') || $key === 'eyebrow' || str_contains($key,'subheading');
                            $isUrl      = str_contains($key,'whatsapp') || str_contains($key,'email') || str_contains($key,'url') || str_contains($key,'link');
                            $label      = ucwords(str_replace('_', ' ', $key));
                        @endphp

                        <div class="mb-4">
                            <label class="form-label fw-semibold small" style="color:#4A3769;">{{ $label }}</label>

                            @if($isImage)
                                @if(!empty($value))
                                    <div class="mb-2 rounded-3 overflow-hidden border" style="max-height:160px;border-color:rgba(186,166,214,.3)!important;">
                                        <img src="{{ str_starts_with($value,'http') ? $value : asset('storage/'.$value) }}"
                                             alt="{{ $label }}" style="width:100%;max-height:160px;object-fit:cover;">
                                    </div>
                                    <p class="form-text">Gambar saat ini. Upload baru untuk mengganti.</p>
                                @endif
                                <div class="mb-2">
                                    <label class="form-label small text-muted">Upload gambar baru</label>
                                    <input type="file" name="{{ $key }}" class="form-control form-control-sm"
                                           accept="image/jpeg,image/png,image/webp">
                                    <div class="form-text">JPG/PNG/WebP, maks 2 MB.</div>
                                </div>
                                <div>
                                    <label class="form-label small text-muted">— atau gunakan URL gambar</label>
                                    <input type="text" name="{{ $key }}_url_override"
                                           class="form-control form-control-sm"
                                           value="{{ str_starts_with($value ?? '','http') ? $value : '' }}"
                                           placeholder="https://example.com/gambar.jpg">
                                    <div class="form-text">Kosongkan jika menggunakan upload di atas.</div>
                                </div>

                            @elseif($isLongText)
                                <textarea name="{{ $key }}" rows="4"
                                          class="form-control @error($key) is-invalid @enderror"
                                          placeholder="{{ $label }}">{{ old($key, $value) }}</textarea>
                                @error($key)<div class="invalid-feedback">{{ $message }}</div>@enderror

                            @elseif($isHeading)
                                <input type="text" name="{{ $key }}"
                                       class="form-control @error($key) is-invalid @enderror"
                                       value="{{ old($key, $value) }}"
                                       style="font-weight:600;">
                                <div class="form-text">Tag HTML diizinkan: <code>&lt;em&gt;kata&lt;/em&gt;</code></div>
                                @error($key)<div class="invalid-feedback">{{ $message }}</div>@enderror

                            @else
                                <input type="text" name="{{ $key }}"
                                       class="form-control @error($key) is-invalid @enderror"
                                       value="{{ old($key, $value) }}"
                                       placeholder="{{ $label }}">
                                @error($key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
                        </div>
                    @endforeach

                    <div class="d-flex gap-2 pt-3 border-top" style="border-color:rgba(186,166,214,.2)!important;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('superadmin.content.show', $cmsPage) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0" style="color:#4A3769;">
                    <i class="fas fa-lightbulb me-2" style="color:#BAA6D6;"></i>Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="small text-muted mb-0 ps-3">
                    <li class="mb-2">Gunakan <code>&lt;em&gt;teks&lt;/em&gt;</code> pada judul untuk efek <em>miring</em>.</li>
                    <li class="mb-2">Untuk gambar: upload file <strong>atau</strong> masukkan URL — jangan keduanya.</li>
                    <li class="mb-2">Ukuran gambar hero: <strong>1200×800px</strong>. Thumbnail: <strong>600×600px</strong>.</li>
                    <li>Perubahan langsung aktif setelah disimpan.</li>
                </ul>
            </div>
        </div>

        @php
            $images = collect($content)->filter(fn($v,$k) =>
                (str_contains($k,'image') || str_contains($k,'img') || str_contains($k,'photo'))
                && !empty($v)
            );
        @endphp
        @if($images->count())
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0" style="color:#4A3769;">Gambar Saat Ini</h6>
                </div>
                <div class="card-body p-2">
                    @foreach($images as $imgKey => $imgVal)
                        <div class="mb-2">
                            <p class="small text-muted mb-1">{{ ucwords(str_replace('_', ' ', $imgKey)) }}</p>
                            <img src="{{ str_starts_with($imgVal,'http') ? $imgVal : asset('storage/'.$imgVal) }}"
                                 class="w-100 rounded-2 border" style="max-height:120px;object-fit:cover;"
                                 alt="{{ $imgKey }}">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
