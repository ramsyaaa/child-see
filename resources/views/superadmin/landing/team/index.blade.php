@extends('superadmin.layout.master')
@section('title', 'Tim Pengembang')
@section('content')

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.landing.index') }}">Landing Content</a></li>
                    <li class="breadcrumb-item active">Tim Pengembang</li>
                </ul>
            </div>
            <div class="col-md-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="page-header-title"><h5 class="m-b-10">Tim Pengembang Website</h5></div>
                <a href="{{ route('superadmin.landing.team.create') }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus me-1"></i>Tambah Anggota
                </a>
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

{{-- Card grid view --}}
@php
    $groups = ['dosen' => 'Dosen Pembimbing', 'mahasiswa' => 'Mahasiswa', 'eksternal' => 'Tim Eksternal'];
    $groupColors = ['dosen' => '#8E77AB', 'mahasiswa' => '#8499B6', 'eksternal' => '#B9A5D6'];
@endphp

@foreach($groups as $groupKey => $groupLabel)
    @php $groupMembers = $members->where('group', $groupKey); @endphp
    @if($groupMembers->isNotEmpty())
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="width:10px;height:10px;border-radius:50%;background:{{ $groupColors[$groupKey] }};display:inline-block;"></span>
            <h6 class="mb-0 fw-bold" style="color:#4A3769;font-size:.85rem;text-transform:uppercase;letter-spacing:.06em;">{{ $groupLabel }}</h6>
            <span style="font-size:.75rem;color:#9ca3af;">({{ $groupMembers->count() }} anggota)</span>
        </div>
        <div class="row g-3">
            @foreach($groupMembers as $m)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100" style="border-radius:14px;overflow:hidden;border-color:rgba(142,119,171,.15);">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start gap-3">
                            {{-- Avatar --}}
                            <div style="flex-shrink:0;">
                                @if($m->photo)
                                    <img src="{{ asset('storage/'.$m->photo) }}" alt="{{ $m->name }}"
                                         style="width:52px;height:52px;border-radius:12px;object-fit:cover;border:2px solid rgba(142,119,171,.2);">
                                @else
                                    <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,{{ $groupColors[$m->group] }}88,{{ $groupColors[$m->group] }});display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;font-weight:700;font-family:'Playfair Display',serif;">
                                        {{ strtoupper(substr($m->name,0,1)) }}
                                    </div>
                                @endif
                            </div>
                            <div style="min-width:0;flex:1;">
                                <div class="fw-bold" style="font-size:.85rem;color:#2E2046;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $m->name }}">{{ $m->name }}</div>
                                @if($m->role_label)
                                    <div style="font-size:.75rem;color:{{ $groupColors[$m->group] }};margin-top:1px;">{{ $m->role_label }}</div>
                                @endif
                                @if($m->affiliation)
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $m->affiliation }}">{{ $m->affiliation }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <div class="d-flex align-items-center gap-2">
                                <span style="padding:.15rem .5rem;border-radius:6px;font-size:.7rem;font-weight:600;background:{{ $groupColors[$m->group] }}15;color:{{ $groupColors[$m->group] }};">{{ ucfirst($m->group) }}</span>
                                @if(!$m->is_active)
                                    <span style="padding:.15rem .5rem;border-radius:6px;font-size:.7rem;background:#f3f4f6;color:#9ca3af;">Nonaktif</span>
                                @endif
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('superadmin.landing.team.edit', $m) }}"
                                   style="width:28px;height:28px;border-radius:7px;background:#F0EDF7;color:#6B5A8E;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:.82rem;"
                                   title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <form action="{{ route('superadmin.landing.team.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus anggota ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="width:28px;height:28px;border-radius:7px;background:#fee2e2;color:#991b1b;border:none;display:inline-flex;align-items:center;justify-content:center;font-size:.82rem;cursor:pointer;"
                                            title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endforeach

@if($members->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="ti ti-users" style="font-size:3rem;opacity:.2;display:block;margin-bottom:.5rem;color:#8E77AB;"></i>
            <p class="text-muted">Belum ada anggota tim. <a href="{{ route('superadmin.landing.team.create') }}">Tambah sekarang</a>.</p>
        </div>
    </div>
@endif

@endsection
