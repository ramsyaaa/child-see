@extends('superadmin.layout.master')
@section('page-title', 'Detail Pengguna')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.inklu.users.index') }}">Pengguna</a></li>
            <li class="active">Detail</li>
        </ul>
        <h2>{{ $user->full_name ?? $user->name }}</h2>
        <p class="inklu-subtitle">{{ $user->email }}</p>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.inklu.users.index') }}" class="btn-banner">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')

<div class="row g-3">
    <div class="col-md-4">
        <div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-body">
                <h6 style="color:#4A3769;font-family:'Playfair Display SC',serif;margin-bottom:1rem;">Info Pengguna</h6>
                <div class="mb-2"><small style="color:rgba(74,55,105,0.55);">Email</small><div style="color:#26223A;">{{ $user->email }}</div></div>
                <div class="mb-2"><small style="color:rgba(74,55,105,0.55);">Bergabung</small><div style="color:#26223A;">{{ $user->created_at->format('d M Y') }}</div></div>
                <div class="mb-2"><small style="color:rgba(74,55,105,0.55);">Total Tes</small><div style="color:#26223A;font-weight:700;font-size:1.2rem;">{{ $results->count() }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                <h6 class="mb-0" style="color:#4A3769;">Riwayat Identifikasi</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background:rgba(186,166,214,0.07);">
                            <tr>
                                <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;padding:0.75rem 1.25rem;border:none;">Anak</th>
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
                                <td style="padding:0.75rem 1.25rem;color:#26223A;font-weight:500;">{{ $r->child_name }}</td>
                                <td><span style="background:rgba(92,71,127,0.08);color:#4A3769;padding:2px 10px;border-radius:20px;font-size:0.8rem;">{{ $typeLabel }}</span></td>
                                <td style="font-weight:600;color:#26223A;">{{ $r->score }}<span style="font-size:0.75rem;font-weight:400;color:rgba(38,34,58,0.45);">/100</span></td>
                                <td><span style="background:{{ $levelColors[$r->level] }}22;color:{{ $levelColors[$r->level] }};padding:2px 10px;border-radius:20px;font-size:0.8rem;font-weight:600;">{{ $levelLabels[$r->level] ?? $r->level }}</span></td>
                                <td style="font-size:0.8rem;color:rgba(38,34,58,0.55);">{{ $r->updated_at->format('d M Y') }}</td>
                                <td><a href="{{ route('superadmin.inklu.results.show', $r) }}" style="color:#5C477F;font-size:0.8rem;">Detail →</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4" style="color:rgba(74,55,105,0.40);">Belum ada riwayat identifikasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
