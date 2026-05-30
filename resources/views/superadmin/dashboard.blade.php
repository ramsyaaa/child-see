@extends('superadmin.layout.master')
@section('page-title', 'Dashboard')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li class="active">Dashboard</li>
        </ul>
        <h2>Dashboard</h2>
        <p class="inklu-subtitle">Ringkasan sistem identifikasi Child See</p>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.assessments.index') }}" class="btn-banner">
            <i class="ti ti-clipboard-list"></i> Semua Asesmen
        </a>
    </div>
</div>
@endsection

@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="inklu-stat-card">
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-label">Total Pengguna</div>
            <i class="fas fa-users" style="position:absolute;bottom:14px;right:18px;font-size:2.5rem;opacity:.15;"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="inklu-stat-card">
            <div class="stat-value">{{ number_format($totalChildren) }}</div>
            <div class="stat-label">Total Anak</div>
            <i class="fas fa-child" style="position:absolute;bottom:14px;right:18px;font-size:2.5rem;opacity:.15;"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="inklu-stat-card">
            <div class="stat-value">{{ number_format($completedAssessments) }}</div>
            <div class="stat-label">Asesmen Selesai</div>
            <i class="fas fa-clipboard-check" style="position:absolute;bottom:14px;right:18px;font-size:2.5rem;opacity:.15;"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="inklu-stat-card" style="background:linear-gradient(135deg,#7B2D2D 0%,#B91C1C 100%);">
            <div class="stat-value">{{ number_format($highIndications) }}</div>
            <div class="stat-label">Indikasi Kuat</div>
            <i class="fas fa-exclamation-triangle" style="position:absolute;bottom:14px;right:18px;font-size:2.5rem;opacity:.15;"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent Assessments --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0" style="color:#4A3769;">Asesmen Terbaru</h5>
                <a href="{{ route('superadmin.assessments.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($recentAssessments->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard-list" style="font-size:3rem;opacity:.2;"></i>
                        <p class="mt-3">Belum ada asesmen yang selesai.</p>
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Anak</th>
                                <th>Kategori</th>
                                <th>Hasil</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAssessments as $a)
                            <tr>
                                <td><a href="{{ route('superadmin.assessments.show', $a) }}" class="text-primary fw-semibold font-monospace small">{{ $a->assessment_code }}</a></td>
                                <td>{{ $a->child->full_name ?? '-' }}</td>
                                <td><span class="badge" style="background:{{ $a->category->color ?? '#5C477F' }};">{{ $a->category->name ?? '-' }}</span></td>
                                <td><span class="badge" style="background:{{ $a->color ?? '#839986' }};">{{ $a->result_label }}</span></td>
                                <td class="small text-muted">{{ $a->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Category Stats --}}
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0" style="color:#4A3769;">Asesmen per Kategori</h6></div>
            <div class="card-body">
                @forelse($categories as $cat)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold">{{ $cat->name }}</span>
                        <span class="small text-muted">{{ $cat->assessments_count }}</span>
                    </div>
                    @php $max = $categories->max('assessments_count') ?: 1; @endphp
                    <div class="progress" style="height:6px;border-radius:3px;">
                        <div class="progress-bar" role="progressbar"
                             style="width:{{ $max > 0 ? round($cat->assessments_count / $max * 100) : 0 }}%;background:{{ $cat->color ?? '#5C477F' }};"
                             aria-valuenow="{{ $cat->assessments_count }}" aria-valuemin="0" aria-valuemax="{{ $max }}"></div>
                    </div>
                </div>
                @empty
                    <p class="text-muted small">Belum ada kategori.</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="mb-0" style="color:#4A3769;">Distribusi Tingkat Indikasi</h6></div>
            <div class="card-body">
                @php
                    $severityMap = [
                        'normal' => ['label' => 'Belum Terindikasi', 'color' => '#839986'],
                        'light'  => ['label' => 'Ringan',            'color' => '#8D77AB'],
                        'medium' => ['label' => 'Sedang',            'color' => '#A86916'],
                        'heavy'  => ['label' => 'Kuat',              'color' => '#dc3545'],
                    ];
                @endphp
                @foreach($severityMap as $key => $info)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small" style="color:{{ $info['color'] }};font-weight:600;">● {{ $info['label'] }}</span>
                    <span class="badge" style="background:{{ $info['color'] }};">{{ $bySeverity[$key] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
