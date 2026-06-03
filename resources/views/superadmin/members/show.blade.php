@extends('superadmin.layout.master')
@section('title', 'Detail Pengguna')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.members.index') }}">Pengguna</a></li>
                    <li class="breadcrumb-item active">{{ $member->name }}</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title"><h5 class="m-b-10">Detail Pengguna</h5></div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="ti ti-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center py-5">
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center"
                     style="width:72px;height:72px;font-size:28px;font-weight:700;background:linear-gradient(135deg,#4A3769,#8E77AB);color:#fff;font-family:'Playfair Display',serif;">
                    {{ strtoupper(substr($member->name,0,1)) }}
                </div>
                <h5 class="mb-1 fw-bold">{{ $member->name }}</h5>
                <p class="text-muted small mb-1">{{ $member->email }}</p>
                @if($member->phone)<p class="text-muted small mb-2"><i class="ti ti-phone me-1"></i>{{ $member->phone }}</p>@endif

                @php $isActive = strtoupper($member->status) === 'ACTIVE'; @endphp
                <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }} px-3 py-2 mb-2">
                    {{ $isActive ? 'Aktif' : 'Ditangguhkan' }}
                </span>
                <div class="mt-2">
                    <span class="badge rounded-pill" style="background:#8E77AB18;color:#4A3769;border:1px solid #8E77AB33;">
                        {{ $member->getRoleLabel() }}
                    </span>
                </div>
                @if(strtoupper($member->role) === 'ORGANIZATION' && $member->organization_name)
                    <div class="mt-3 p-3 rounded text-start" style="background:#8E77AB0D;border:1px solid #8E77AB22;">
                        <div class="small fw-semibold" style="color:#4A3769;">{{ $member->organization_name }}</div>
                        <div class="small text-muted">{{ $member->organization_type }}</div>
                        <div class="small mt-1">Kuota Anak: <strong style="color:#8E77AB;">{{ $member->child_quota }}</strong></div>
                    </div>
                @endif
                <p class="text-muted small mt-3 mb-0">Bergabung {{ $member->created_at->format('d M Y') }}</p>
            </div>
            <div class="card-footer bg-transparent d-grid gap-2">
                <a href="{{ route('superadmin.members.edit', $member) }}" class="btn btn-primary">
                    <i class="ti ti-edit me-2"></i>Edit Profil
                </a>
                <form action="{{ route('superadmin.members.toggle-status', $member) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline-{{ $isActive ? 'warning' : 'success' }} w-100"
                            onclick="return confirm('{{ $isActive ? 'Tangguhkan' : 'Aktifkan kembali' }} akun ini?')">
                        <i class="ti ti-{{ $isActive ? 'ban' : 'check' }} me-2"></i>
                        {{ $isActive ? 'Tangguhkan Akun' : 'Aktifkan Kembali' }}
                    </button>
                </form>
                <a href="{{ route('superadmin.members.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    {{-- Detail Content --}}
    <div class="col-lg-8">
        {{-- Children --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Anak ({{ $children->count() }} / {{ $member->child_quota }})</h5>
            </div>
            @if($children->isEmpty())
                <div class="card-body text-muted text-center py-4">
                    <i class="ti ti-user-x" style="font-size:2rem;opacity:.3;display:block;margin-bottom:6px;"></i>
                    Belum ada data anak terdaftar.
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($children as $child)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avtar avtar-xs" style="background:#8499B622;flex-shrink:0;">
                                <i class="ti ti-user" style="color:#8499B6;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:13px;">{{ $child->full_name }}</div>
                                <div class="text-muted" style="font-size:11px;">
                                    {{ $child->school_name ?? '—' }}
                                    @if($child->birth_date) · {{ \Carbon\Carbon::parse($child->birth_date)->age }} thn @endif
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark border">{{ $child->assessments->count() }} asesmen</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Assessments --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Riwayat Asesmen Terbaru</h5>
            </div>
            @if($assessments->isEmpty())
                <div class="card-body text-muted text-center py-4">
                    <i class="ti ti-clipboard-x" style="font-size:2rem;opacity:.3;display:block;margin-bottom:6px;"></i>
                    Belum ada asesmen.
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($assessments as $a)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <div class="fw-semibold" style="font-size:13px;">
                                {{ $a->child->full_name ?? '—' }}
                                <span class="text-muted fw-normal">— {{ $a->category->name ?? '—' }}</span>
                            </div>
                            <div class="text-muted" style="font-size:11px;">
                                {{ $a->assessment_date ?? $a->created_at->format('d M Y') }}
                                · {{ $a->assessment_code }}
                            </div>
                        </div>
                        <div class="text-end">
                            @if($a->severity_level)
                                <span class="badge" style="background:{{ $a->color ?? '#8E77AB' }}22;color:{{ $a->color ?? '#8E77AB' }};border:1px solid {{ $a->color ?? '#8E77AB' }}33;font-size:10px;">
                                    {{ $a->severity_label }}
                                </span>
                            @endif
                            <div class="text-muted mt-1" style="font-size:11px;">Skor: {{ $a->total_score ?? '—' }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
