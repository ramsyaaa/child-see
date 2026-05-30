@extends('superadmin.layout.master')
@section('page-title', 'Detail Asesmen – ' . $assessment->assessment_code)

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.assessments.index') }}">Hasil Asesmen</a></li>
            <li class="active">{{ $assessment->assessment_code }}</li>
        </ul>
        <h2>Detail Asesmen</h2>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.assessments.index') }}" class="btn-banner">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')

@php
    $sevColors = [
        'normal'=>'#839986','light'=>'#8D77AB','medium'=>'#A86916','heavy'=>'#dc3545'
    ];
    $sevLabels = [
        'normal'=>'Belum Terindikasi','light'=>'Terindikasi Ringan',
        'medium'=>'Terindikasi Sedang','heavy'=>'Terindikasi Kuat'
    ];
    $svColor = $sevColors[$assessment->severity_level ?? ''] ?? ($assessment->color ?? '#6c757d');
    $svLabel = $sevLabels[$assessment->severity_level ?? ''] ?? ($assessment->severity_level ?? '—');
@endphp

{{-- Severity Banner --}}
<div class="alert mb-4 d-flex align-items-center gap-3"
    style="background:{{ $svColor }}1a;border-left:5px solid {{ $svColor }};border-radius:8px;">
    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
        style="width:48px;height:48px;background:{{ $svColor }};color:#fff;font-size:1.4rem;">
        <i class="ti ti-shield-check"></i>
    </div>
    <div>
        <div class="fw-bold" style="font-size:1.1rem;color:{{ $svColor }};">{{ $svLabel }}</div>
        <div class="text-muted small">Skor Total: <strong>{{ number_format($assessment->total_score ?? 0, 2) }}</strong></div>
    </div>
</div>

<div class="row g-4">
    {{-- Assessment Info --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Informasi Asesmen</h5></div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:40%">Kode</th>
                        <td><code>{{ $assessment->assessment_code }}</code></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Kategori</th>
                        <td>{{ $assessment->category->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tanggal</th>
                        <td>{{ $assessment->created_at ? $assessment->created_at->format('d M Y, H:i') : '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Skor Total</th>
                        <td><strong>{{ number_format($assessment->total_score ?? 0, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tingkat</th>
                        <td>
                            <span class="badge" style="background:{{ $svColor }};">{{ $svLabel }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Child Info --}}
        <div class="card mt-3">
            <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Data Anak</h5></div>
            <div class="card-body">
                @if($assessment->child)
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:40%">Nama</th>
                        <td><strong>{{ $assessment->child->full_name }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Jenis Kelamin</th>
                        <td>{{ $assessment->child->gender ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tanggal Lahir</th>
                        <td>
                            @if($assessment->child->birth_date)
                                {{ \Carbon\Carbon::parse($assessment->child->birth_date)->format('d M Y') }}
                                <small class="text-muted">({{ \Carbon\Carbon::parse($assessment->child->birth_date)->age }} thn)</small>
                            @else —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Kelas</th>
                        <td>{{ $assessment->child->class_name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Sekolah</th>
                        <td>{{ $assessment->child->school_name ?? '—' }}</td>
                    </tr>
                </table>
                @else
                    <p class="text-muted mb-0">Data anak tidak ditemukan.</p>
                @endif
            </div>
        </div>

        {{-- User Info --}}
        <div class="card mt-3">
            <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Pengguna (Pelapor)</h5></div>
            <div class="card-body">
                @if($assessment->user)
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:40%">Nama</th>
                        <td>{{ $assessment->user->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email</th>
                        <td>{{ $assessment->user->email }}</td>
                    </tr>
                </table>
                @else
                    <p class="text-muted mb-0">Data pengguna tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        {{-- Domain Scores --}}
        @if(!empty($assessment->domainScores) || (isset($domainScores) && count($domainScores)))
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Skor per Domain</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Skor</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assessment->domainScores as $ds)
                            @php
                                $dsScore  = $ds->total_score ?? 0;
                                $dsMax    = $assessment->answers->where('questionnaire.domain_id', $ds->domain_id)->count() * 2 ?: 1;
                                $dsPct    = min(100, round($dsScore / $dsMax * 100));
                                $dsc      = $sevColors[$ds->severity_level ?? ''] ?? $svColor;
                            @endphp
                            <tr>
                                <td>{{ $ds->domain->name ?? '—' }}</td>
                                <td><strong>{{ number_format($dsScore, 1) }}</strong></td>
                                <td>
                                    <div class="progress" style="height:8px;">
                                        <div class="progress-bar" style="width:{{ $dsPct }}%;background:{{ $dsc }};" role="progressbar"></div>
                                    </div>
                                    <small class="text-muted">{{ $ds->result_label ?? '—' }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Q&A Answers --}}
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Jawaban Pertanyaan</h5></div>
            <div class="card-body p-0">
                @if($assessment->answers->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:50%">Pertanyaan</th>
                                <th>Domain</th>
                                <th>Jawaban</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assessment->answers as $ans)
                            <tr>
                                <td class="small">{{ $ans->questionnaire->question ?? '—' }}</td>
                                <td class="small">{{ $ans->questionnaire->domain->name ?? '—' }}</td>
                                <td>
                                    @php $sc = $ans->score ?? 0; @endphp
                                    @if($sc == 0)
                                        <span class="badge bg-secondary">Tidak Pernah</span>
                                    @elseif($sc == 1)
                                        <span class="badge bg-warning text-dark">Kadang-Kadang</span>
                                    @else
                                        <span class="badge bg-danger">Sering</span>
                                    @endif
                                </td>
                                <td><strong>{{ $ans->score }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-muted p-3 mb-0">Tidak ada data jawaban.</p>
                @endif
            </div>
        </div>

        {{-- Recommendation --}}
        @if($assessment->recommendation ?? false)
        <div class="card">
            <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Rekomendasi</h5></div>
            <div class="card-body">
                <div class="p-3 rounded" style="background:{{ $svColor }}1a;border-left:4px solid {{ $svColor }};">
                    <p class="mb-0">{{ $assessment->recommendation }}</p>
                </div>
            </div>
        </div>
        @elseif(isset($rule) && $rule->recommendation)
        <div class="card">
            <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Rekomendasi</h5></div>
            <div class="card-body">
                <div class="p-3 rounded" style="background:{{ $svColor }}1a;border-left:4px solid {{ $svColor }};">
                    <p class="mb-0">{{ $rule->recommendation }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection