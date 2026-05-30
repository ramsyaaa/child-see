@extends('superadmin.layout.master')
@section('page-title', 'Hasil Identifikasi')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.dashboard') }}">InkluSyncID</a></li>
            <li class="active">Hasil Identifikasi</li>
        </ul>
        <h2>Hasil Identifikasi</h2>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
        <h6 class="mb-0" style="color:#4A3769;">{{ $results->total() }} hasil ditemukan</h6>
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input name="search" value="{{ request('search') }}" placeholder="Nama anak / pengisi / email…" class="form-control form-control-sm" style="width:200px;border-color:#BAA6D6;">
            <select name="type" class="form-select form-select-sm" style="width:150px;border-color:#BAA6D6;">
                <option value="">Semua Jenis</option>
                <option value="penglihatan" {{ request('type')=='penglihatan' ? 'selected' : '' }}>Penglihatan</option>
                <option value="pendengaran" {{ request('type')=='pendengaran' ? 'selected' : '' }}>Pendengaran</option>
                <option value="intelektual" {{ request('type')=='intelektual' ? 'selected' : '' }}>Intelektual</option>
            </select>
            <select name="level" class="form-select form-select-sm" style="width:150px;border-color:#BAA6D6;">
                <option value="">Semua Indikasi</option>
                <option value="low" {{ request('level')=='low' ? 'selected' : '' }}>Rendah</option>
                <option value="mid" {{ request('level')=='mid' ? 'selected' : '' }}>Sedang</option>
                <option value="high" {{ request('level')=='high' ? 'selected' : '' }}>Tinggi</option>
            </select>
            <button class="btn btn-sm" style="background:#5C477F;color:#fff;">Filter</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:rgba(186,166,214,0.07);">
                    <tr>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;padding:0.75rem 1.25rem;border:none;">Nama Anak</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Pengisi / Akun</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Jenis</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Skor</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Indikasi</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Tanggal</th>
                        <th style="border:none;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $r)
                    @php
                    $levelColors = ['low' => '#839986', 'mid' => '#8D77AB', 'high' => '#A86916'];
                    $levelLabels = ['low' => 'Rendah', 'mid' => 'Sedang', 'high' => 'Tinggi'];
                    $typeLabel   = ['penglihatan' => 'Penglihatan', 'pendengaran' => 'Pendengaran', 'intelektual' => 'Intelektual'][$r->type] ?? $r->type;
                    @endphp
                    <tr>
                        <td style="padding:0.75rem 1.25rem;color:#26223A;font-weight:500;">{{ $r->child_name }}<br><small style="color:rgba(38,34,58,0.45);font-weight:400;">Usia {{ $r->child_age }} thn</small></td>
                        <td style="color:rgba(38,34,58,0.65);font-size:0.875rem;">
                            {{ $r->filler_name }} ({{ $r->filler_status }})<br>
                            <small style="color:rgba(38,34,58,0.40);">{{ $r->user?->email ?? '—' }}</small>
                        </td>
                        <td><span style="background:rgba(92,71,127,0.08);color:#4A3769;padding:2px 10px;border-radius:20px;font-size:0.8rem;">{{ $typeLabel }}</span></td>
                        <td style="font-weight:600;color:#26223A;">{{ $r->score }}<span style="font-size:0.75rem;font-weight:400;color:rgba(38,34,58,0.45);">/100</span></td>
                        <td><span style="background:{{ $levelColors[$r->level] }}22;color:{{ $levelColors[$r->level] }};padding:2px 10px;border-radius:20px;font-size:0.8rem;font-weight:600;">{{ $levelLabels[$r->level] ?? $r->level }}</span></td>
                        <td style="font-size:0.8rem;color:rgba(38,34,58,0.55);">{{ $r->updated_at->format('d M Y') }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('superadmin.inklu.results.show', $r) }}" style="color:#5C477F;font-size:0.8rem;">Detail</a>
                            <form action="{{ route('superadmin.inklu.results.destroy', $r) }}" method="POST" onsubmit="return confirm('Hapus hasil ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#c0392b;font-size:0.8rem;cursor:pointer;padding:0;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4" style="color:rgba(74,55,105,0.40);">Tidak ada data ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer" style="background:transparent;border-top:1px solid rgba(186,166,214,0.2);">
        {{ $results->links() }}
    </div>
</div>
@endsection
