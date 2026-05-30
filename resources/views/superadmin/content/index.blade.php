@extends('superadmin.layout.master')
@section('page-title', 'Konten Halaman')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.dashboard') }}">Pengaturan</a></li>
            <li class="active">Konten Halaman</li>
        </ul>
        <h2>Konten Halaman</h2>
        <p class="inklu-subtitle">Kelola teks, gambar, dan SEO untuk setiap halaman publik</p>
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

@if($pages->isEmpty())
    <div class="card text-center py-5">
        <div class="card-body">
            <div style="width:64px;height:64px;border-radius:50%;background:rgba(186,166,214,.15);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="fas fa-file-alt" style="font-size:1.6rem;color:#BAA6D6;"></i>
            </div>
            <h5 style="color:#4A3769;">Belum ada halaman</h5>
            <p class="text-muted mb-3">Jalankan seeder untuk mengisi konten halaman.</p>
            <code class="d-block bg-light rounded px-3 py-2 text-start" style="max-width:420px;margin:0 auto;font-size:0.82rem;">
                php artisan db:seed --class=CmsSeeder
            </code>
        </div>
    </div>
@else
<div class="row g-4">
    @php
        $pageIcons = [
            'home'          => ['icon' => 'fa-home',         'color' => '#2E2046'],
            'about'         => ['icon' => 'fa-info-circle',  'color' => '#4A3769'],
            'identifikasi'  => ['icon' => 'fa-clipboard-check','color'=> '#5C477F'],
            'kontak'        => ['icon' => 'fa-envelope',     'color' => '#839986'],
            'classes'       => ['icon' => 'fa-list-alt',     'color' => '#4A3769'],
            'instructors'   => ['icon' => 'fa-user-tie',     'color' => '#5C477F'],
            'bundles'       => ['icon' => 'fa-tags',         'color' => '#A86916'],
            'schedule'      => ['icon' => 'fa-calendar-alt', 'color' => '#1E3A5F'],
        ];
    @endphp

    @foreach($pages as $page)
        @php $meta = $pageIcons[$page->slug] ?? ['icon' => 'fa-file-alt', 'color' => '#5C477F']; @endphp
        <div class="col-sm-6 col-lg-4">
            <div class="card h-100" style="transition:box-shadow .2s;">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-3 p-3 flex-shrink-0"
                             style="background:{{ $meta['color'] }};min-width:46px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas {{ $meta['icon'] }} text-white" style="font-size:1.1rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0" style="color:#2E2046;font-family:'Playfair Display SC',serif;font-size:1rem;">
                                {{ $page->title }}
                            </h5>
                            <small class="text-muted">
                                {{ $page->sections_count }}
                                seksi{{ $page->sections_count != 1 ? '' : '' }}
                            </small>
                        </div>
                    </div>

                    <div class="mb-3 d-flex flex-wrap gap-1">
                        @if($page->seo_title)
                            <span class="badge" style="background:rgba(131,153,134,.15);color:#1f4d22;border:1px solid rgba(131,153,134,.3);">
                                <i class="fas fa-check me-1"></i>SEO
                            </span>
                        @else
                            <span class="badge" style="background:rgba(220,180,0,.1);color:#7a5c00;border:1px solid rgba(220,180,0,.25);">
                                <i class="fas fa-exclamation me-1"></i>SEO kosong
                            </span>
                        @endif
                        @if($page->og_image)
                            <span class="badge" style="background:rgba(92,71,127,.12);color:#4A3769;border:1px solid rgba(92,71,127,.25);">
                                <i class="fas fa-image me-1"></i>OG image
                            </span>
                        @endif
                    </div>

                    <p class="text-muted small mb-0" style="line-height:1.5;">
                        {{ $page->seo_description
                            ? \Str::limit($page->seo_description, 90)
                            : 'Belum ada deskripsi SEO.' }}
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('superadmin.content.show', $page) }}"
                       class="btn btn-primary w-100">
                        <i class="fas fa-edit me-2"></i>Edit Konten & SEO
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif
@endsection
