@extends('superadmin.layout.master')
@section('page-title', 'Detail Hasil')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.inklu.results.index') }}">Hasil Identifikasi</a></li>
            <li class="active">Detail</li>
        </ul>
        <h2>Detail Hasil Identifikasi</h2>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.inklu.results.index') }}" class="btn-banner">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection

@section('content')

@php
$levelColors = ['low' => '#839986', 'mid' => '#8D77AB', 'high' => '#A86916'];
$levelLabels = ['low' => 'Rendah', 'mid' => 'Sedang', 'high' => 'Tinggi'];
$typeColors  = ['penglihatan' => '#1E3A5F', 'pendengaran' => '#8D77AB', 'intelektual' => '#A86916'];
$typeLabels  = ['penglihatan' => 'Hambatan Penglihatan', 'pendengaran' => 'Hambatan Pendengaran', 'intelektual' => 'Hambatan Intelektual'];
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-body">
                <div style="text-align:center;padding:1rem 0 1.5rem;">
                    <div style="width:70px;height:70px;border-radius:50%;background:{{ $typeColors[$result->type] }}18;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
                        <span style="font-size:2rem;font-weight:700;color:{{ $typeColors[$result->type] }};">{{ $result->score }}</span>
                    </div>
                    <div style="font-size:0.7rem;color:rgba(74,55,105,0.50);">Skor / 100</div>
                    <div style="margin-top:0.75rem;">
                        <span style="background:{{ $levelColors[$result->level] }}22;color:{{ $levelColors[$result->level] }};padding:4px 14px;border-radius:20px;font-size:0.85rem;font-weight:700;">
                            Indikasi {{ $levelLabels[$result->level] }}
                        </span>
                    </div>
                </div>

                <hr style="border-color:rgba(186,166,214,0.25);">
                <div class="mb-2"><small style="color:rgba(74,55,105,0.55);">Nama Anak</small><div style="color:#26223A;font-weight:600;">{{ $result->child_name }}</div></div>
                <div class="mb-2"><small style="color:rgba(74,55,105,0.55);">Usia</small><div style="color:#26223A;">{{ $result->child_age }} tahun</div></div>
                <div class="mb-2"><small style="color:rgba(74,55,105,0.55);">TTL</small><div style="color:#26223A;">{{ $result->child_dob }}</div></div>
                <div class="mb-2"><small style="color:rgba(74,55,105,0.55);">Pengisi</small><div style="color:#26223A;">{{ $result->filler_name }} ({{ $result->filler_status }})</div></div>
                <div class="mb-2"><small style="color:rgba(74,55,105,0.55);">Akun</small><div style="color:#26223A;">{{ $result->user?->email ?? '—' }}</div></div>
                <div class="mb-2"><small style="color:rgba(74,55,105,0.55);">Jenis Hambatan</small><div style="color:{{ $typeColors[$result->type] }};font-weight:600;">{{ $typeLabels[$result->type] }}</div></div>
                <div><small style="color:rgba(74,55,105,0.55);">Tanggal Tes</small><div style="color:#26223A;">{{ $result->updated_at->format('d M Y, H:i') }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                <h6 class="mb-0" style="color:#4A3769;">Jawaban Per Butir</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead style="background:rgba(186,166,214,0.07);">
                            <tr>
                                <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;padding:0.75rem 1.25rem;border:none;width:50px;">#</th>
                                <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result->answers as $idx => $val)
                            @php
                            $ansColors = ['yes' => '#1E3A5F', 'sometimes' => '#8D77AB', 'no' => '#839986'];
                            $ansLabels = ['yes' => 'Ya', 'sometimes' => 'Kadang', 'no' => 'Tidak'];
                            @endphp
                            <tr>
                                <td style="padding:0.6rem 1.25rem;color:rgba(38,34,58,0.40);font-size:0.8rem;">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</td>
                                <td>
                                    <span style="background:{{ $ansColors[$val] ?? '#ccc' }}18;color:{{ $ansColors[$val] ?? '#666' }};padding:2px 10px;border-radius:20px;font-size:0.8rem;font-weight:600;">
                                        {{ $ansLabels[$val] ?? $val }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
