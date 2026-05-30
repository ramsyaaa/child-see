@extends('superadmin.layout.master')
@section('page-title', 'Dashboard InkluSyncID')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li class="active">Dashboard</li>
        </ul>
        <h2>Dashboard InkluSyncID</h2>
        <p class="inklu-subtitle">Ringkasan aktivitas identifikasi anak berkebutuhan khusus</p>
    </div>
</div>
@endsection

@section('content')

<!-- Stats Row -->
<div class="row g-3 mb-4">
    @php
    $stats = [
        ['label' => 'Total Pengguna', 'value' => $totalUsers, 'icon' => 'fas fa-users', 'color' => '#5C477F', 'bg' => 'rgba(92,71,127,0.10)'],
        ['label' => 'Total Tes', 'value' => $totalTests, 'icon' => 'fas fa-clipboard-check', 'color' => '#8D77AB', 'bg' => 'rgba(141,119,171,0.10)'],
        ['label' => 'Bulan Ini', 'value' => $thisMonth, 'icon' => 'fas fa-calendar-day', 'color' => '#1E3A5F', 'bg' => 'rgba(30,58,95,0.10)'],
        ['label' => 'Indikasi Tinggi', 'value' => $highIndications, 'icon' => 'fas fa-exclamation-triangle', 'color' => '#A86916', 'bg' => 'rgba(168,105,22,0.10)'],
    ];
    @endphp
    @foreach($stats as $s)
    <div class="col-md-6 col-xl-3">
        <div class="card h-100" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:14px;background:{{ $s['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="{{ $s['icon'] }}" style="font-size:1.3rem;color:{{ $s['color'] }};"></i>
                </div>
                <div>
                    <div style="font-size:1.75rem;font-weight:700;color:#26223A;font-family:'Playfair Display SC',serif;line-height:1;">{{ $s['value'] }}</div>
                    <div style="font-size:0.8rem;color:rgba(38,34,58,0.55);margin-top:2px;">{{ $s['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <!-- By Type -->
    <div class="col-md-4">
        <div class="card h-100" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                <h6 class="mb-0" style="color:#4A3769;font-family:'Playfair Display SC',serif;">Per Jenis Hambatan</h6>
            </div>
            <div class="card-body">
                @php
                $typeLabels = ['penglihatan' => ['Penglihatan', '#1E3A5F'], 'pendengaran' => ['Pendengaran', '#8D77AB'], 'intelektual' => ['Intelektual', '#A86916']];
                $typeTotal = array_sum($byType->toArray()) ?: 1;
                @endphp
                @foreach($typeLabels as $key => [$label, $color])
                @php $count = $byType->get($key, 0); $pct = round($count / $typeTotal * 100); @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small style="color:#4A3769;font-weight:600;">{{ $label }}</small>
                        <small style="color:rgba(74,55,105,0.60);">{{ $count }} ({{ $pct }}%)</small>
                    </div>
                    <div style="height:6px;background:#E9E9EB;border-radius:3px;overflow:hidden;">
                        <div style="width:{{ $pct }}%;height:100%;background:{{ $color }};border-radius:3px;transition:width .5s;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- By Level -->
    <div class="col-md-4">
        <div class="card h-100" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                <h6 class="mb-0" style="color:#4A3769;font-family:'Playfair Display SC',serif;">Per Tingkat Indikasi</h6>
            </div>
            <div class="card-body">
                @php
                $levelLabels = ['low' => ['Rendah', '#839986'], 'mid' => ['Sedang', '#8D77AB'], 'high' => ['Tinggi', '#A86916']];
                $levelTotal  = array_sum($byLevel->toArray()) ?: 1;
                @endphp
                @foreach($levelLabels as $key => [$label, $color])
                @php $count = $byLevel->get($key, 0); $pct = round($count / $levelTotal * 100); @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small style="color:#4A3769;font-weight:600;">Indikasi {{ $label }}</small>
                        <small style="color:rgba(74,55,105,0.60);">{{ $count }} ({{ $pct }}%)</small>
                    </div>
                    <div style="height:6px;background:#E9E9EB;border-radius:3px;overflow:hidden;">
                        <div style="width:{{ $pct }}%;height:100%;background:{{ $color }};border-radius:3px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Monthly Trend -->
    <div class="col-md-4">
        <div class="card h-100" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                <h6 class="mb-0" style="color:#4A3769;font-family:'Playfair Display SC',serif;">Tren 6 Bulan Terakhir</h6>
            </div>
            <div class="card-body" style="display:flex;align-items:flex-end;gap:0.5rem;height:120px;padding-bottom:1rem;">
                @php
                $maxTrend = $monthlyTrend->max('total') ?: 1;
                $months   = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                @endphp
                @foreach($monthlyTrend as $m)
                @php $h = max(4, round($m->total / $maxTrend * 80)); @endphp
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;">
                    <div style="font-size:0.6rem;color:rgba(74,55,105,0.55);">{{ $m->total }}</div>
                    <div style="width:100%;height:{{ $h }}px;background:#BAA6D6;border-radius:3px 3px 0 0;"></div>
                    <div style="font-size:0.6rem;color:rgba(74,55,105,0.55);">{{ $months[$m->month - 1] }}</div>
                </div>
                @endforeach
                @if($monthlyTrend->isEmpty())
                <div style="width:100%;text-align:center;color:rgba(74,55,105,0.40);font-size:0.8rem;">Belum ada data</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Results -->
<div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
        <h6 class="mb-0" style="color:#4A3769;font-family:'Playfair Display SC',serif;">Hasil Identifikasi Terbaru</h6>
        <a href="{{ route('superadmin.inklu.results.index') }}" style="font-size:0.8rem;color:#5C477F;">Lihat semua →</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:rgba(186,166,214,0.07);">
                    <tr>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;padding:0.75rem 1.25rem;border:none;">Nama Anak</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Pengisi</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Jenis</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Skor</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Indikasi</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentResults as $r)
                    @php
                    $levelColors = ['low' => '#839986', 'mid' => '#8D77AB', 'high' => '#A86916'];
                    $levelLabels = ['low' => 'Rendah', 'mid' => 'Sedang', 'high' => 'Tinggi'];
                    $typeLabel   = ['penglihatan' => 'Penglihatan', 'pendengaran' => 'Pendengaran', 'intelektual' => 'Intelektual'][$r->type] ?? $r->type;
                    @endphp
                    <tr>
                        <td style="padding:0.75rem 1.25rem;color:#26223A;font-weight:500;">{{ $r->child_name }}</td>
                        <td style="color:rgba(38,34,58,0.65);font-size:0.875rem;">
                            {{ $r->filler_name }}<br>
                            <small style="color:rgba(38,34,58,0.40);">{{ $r->user?->email }}</small>
                        </td>
                        <td><span style="background:rgba(92,71,127,0.08);color:#4A3769;padding:2px 10px;border-radius:20px;font-size:0.8rem;">{{ $typeLabel }}</span></td>
                        <td style="font-weight:600;color:#26223A;">{{ $r->score }}<span style="font-size:0.75rem;font-weight:400;color:rgba(38,34,58,0.45);">/100</span></td>
                        <td><span style="background:{{ $levelColors[$r->level] }}22;color:{{ $levelColors[$r->level] }};padding:2px 10px;border-radius:20px;font-size:0.8rem;font-weight:600;">{{ $levelLabels[$r->level] ?? $r->level }}</span></td>
                        <td style="font-size:0.8rem;color:rgba(38,34,58,0.55);">{{ $r->updated_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4" style="color:rgba(74,55,105,0.40);">Belum ada data identifikasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
