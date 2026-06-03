@extends('superadmin.layout.master')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Manajemen Pengguna</li>
                </ul>
            </div>
            <div class="col-md-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="page-header-title"><h5 class="m-b-10">Manajemen Pengguna</h5></div>
                <a href="{{ route('superadmin.members.create') }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-user-plus me-1"></i>Buat Pengguna
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

{{-- Summary stat cards --}}
<div class="row g-3 mb-4">
    @php
        $tabMeta = [
            'member'       => ['label'=>'Orang Tua / Guru',   'color'=>'#8E77AB', 'bg'=>'#8E77AB18', 'icon'=>'ti-users'],
            'organization' => ['label'=>'Organisasi',         'color'=>'#8499B6', 'bg'=>'#8499B618', 'icon'=>'ti-building'],
            'admin'        => ['label'=>'Admin / Superadmin', 'color'=>'#6B5A8E', 'bg'=>'#6B5A8E18', 'icon'=>'ti-shield'],
        ];
    @endphp
    @foreach($tabMeta as $key => $meta)
    <div class="col-6 col-sm-4 col-md-3">
        <a href="{{ route('superadmin.members.index', ['tab'=>$key]) }}" style="text-decoration:none;">
            <div class="card h-100 border-0" style="border-left:3px solid {{ $meta['color'] }} !important;border-radius:12px;">
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div style="width:40px;height:40px;border-radius:10px;background:{{ $meta['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="ti {{ $meta['icon'] }}" style="color:{{ $meta['color'] }};font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:1.4rem;line-height:1.1;color:#2E2046;">{{ $stats[$key] }}</div>
                        <div style="font-size:.72rem;color:#6b7280;margin-top:1px;">{{ $meta['label'] }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
    <div class="col-6 col-sm-4 col-md-3">
        <div class="card h-100 border-0" style="border-left:3px solid #198754 !important;border-radius:12px;">
            <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                <div style="width:40px;height:40px;border-radius:10px;background:#19875418;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="ti ti-user-check" style="color:#198754;font-size:1.2rem;"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.4rem;line-height:1.1;color:#2E2046;">{{ $stats['active'] }}</div>
                    <div style="font-size:.72rem;color:#6b7280;margin-top:1px;">Aktif (tab ini)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="border-radius:16px;overflow:hidden;">

    {{-- Custom pill tabs --}}
    <div style="background:#F0EDF7;padding:1rem 1.25rem 0;border-bottom:1px solid rgba(142,119,171,.15);">
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
            @foreach(['member'=>['label'=>'Orang Tua / Guru','color'=>'#8E77AB'],'organization'=>['label'=>'Organisasi','color'=>'#8499B6'],'admin'=>['label'=>'Admin / Superadmin','color'=>'#6B5A8E']] as $key=>$meta)
            <a href="{{ route('superadmin.members.index', array_merge(request()->except('tab','page'),['tab'=>$key])) }}"
               style="display:inline-flex;align-items:center;gap:.45rem;padding:.5rem 1rem;border-radius:8px 8px 0 0;font-size:.82rem;font-weight:600;text-decoration:none;transition:all .15s;
               {{ $tab===$key ? 'background:#fff;color:#4A3769;border:1px solid rgba(142,119,171,.2);border-bottom:1px solid #fff;margin-bottom:-1px;' : 'background:transparent;color:#6b7280;border:1px solid transparent;' }}">
                <i class="ti {{ ['member'=>'ti-users','organization'=>'ti-building','admin'=>'ti-shield'][$key] }}" style="font-size:.9rem;"></i>
                {{ $meta['label'] }}
                <span style="display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:50%;background:{{ $tab===$key ? $meta['color'] : 'rgba(107,90,142,.15)' }};color:{{ $tab===$key ? '#fff' : '#6b7280' }};font-size:10px;font-weight:700;line-height:1;">{{ $stats[$key] }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <div class="card-body" style="background:#fff;padding:1.25rem;">

        {{-- Search bar --}}
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1.25rem;align-items:center;">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div style="flex:1;min-width:200px;">
                <div style="position:relative;">
                    <i class="ti ti-search" style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.9rem;"></i>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                           style="padding-left:2.2rem;border-radius:8px;border-color:rgba(142,119,171,.25);"
                           placeholder="Cari nama, email{{ $tab==='organization' ? ', organisasi' : '' }}...">
                </div>
            </div>
            <select name="status" class="form-select" style="width:auto;min-width:140px;border-radius:8px;border-color:rgba(142,119,171,.25);">
                <option value="">Semua Status</option>
                <option value="active"    @selected(request('status')==='active')>Aktif</option>
                <option value="suspended" @selected(request('status')==='suspended')>Ditangguhkan</option>
            </select>
            <button class="btn btn-primary btn-sm" style="padding:.45rem .9rem;border-radius:8px;">
                <i class="ti ti-search me-1"></i>Cari
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('superadmin.members.index',['tab'=>$tab]) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                <i class="ti ti-x"></i>
            </a>
            @endif
        </form>

        {{-- Table --}}
        <div class="table-responsive" style="border-radius:10px;border:1px solid rgba(142,119,171,.12);">
            <table class="table table-hover align-middle mb-0" style="font-size:.88rem;">
                <thead style="background:#F7F5FC;">
                    <tr>
                        <th style="width:40px;padding:.65rem .75rem;font-size:.7rem;color:#6b7280;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">#</th>
                        <th style="padding:.65rem .75rem;font-size:.7rem;color:#6b7280;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">Pengguna</th>
                        @if($tab==='organization')
                            <th style="padding:.65rem .75rem;font-size:.7rem;color:#6b7280;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">Organisasi</th>
                            <th style="padding:.65rem .75rem;font-size:.7rem;color:#6b7280;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">Kuota</th>
                        @else
                            <th style="padding:.65rem .75rem;font-size:.7rem;color:#6b7280;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">Telepon</th>
                            <th style="padding:.65rem .75rem;font-size:.7rem;color:#6b7280;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">Anak</th>
                        @endif
                        <th style="padding:.65rem .75rem;font-size:.7rem;color:#6b7280;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">Status</th>
                        <th style="padding:.65rem .75rem;font-size:.7rem;color:#6b7280;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">Bergabung</th>
                        <th style="padding:.65rem .75rem;width:56px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                    <tr style="border-color:rgba(142,119,171,.1);">
                        <td style="padding:.6rem .75rem;color:#9ca3af;font-size:.8rem;">{{ $members->firstItem() + $loop->index }}</td>
                        <td style="padding:.6rem .75rem;">
                            <div style="display:flex;align-items:center;gap:.6rem;">
                                <div style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#4A3769,#8E77AB);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff;font-weight:700;font-size:.82rem;">
                                    {{ strtoupper(substr($m->name,0,1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;color:#1f2937;font-size:.86rem;">{{ $m->name }}</div>
                                    <div style="font-size:.75rem;color:#9ca3af;">{{ $m->email }}</div>
                                    @if($m->username)<div style="font-size:.72rem;color:#b9a5d6;">@{{ $m->username }}</div>@endif
                                </div>
                            </div>
                        </td>
                        @if($tab==='organization')
                            <td style="padding:.6rem .75rem;">
                                <div style="font-size:.85rem;color:#1f2937;">{{ $m->organization_name ?? '—' }}</div>
                                <div style="font-size:.75rem;color:#9ca3af;">{{ $m->organization_type ?? '' }}</div>
                            </td>
                            <td style="padding:.6rem .75rem;">
                                <span style="display:inline-flex;align-items:center;gap:.25rem;padding:.2rem .55rem;border-radius:6px;background:#8E77AB12;color:#6B5A8E;font-size:.78rem;font-weight:600;border:1px solid #8E77AB22;">
                                    {{ $m->children_count }} / {{ $m->child_quota }}
                                </span>
                            </td>
                        @else
                            <td style="padding:.6rem .75rem;color:#6b7280;font-size:.84rem;">{{ $m->phone ?? '—' }}</td>
                            <td style="padding:.6rem .75rem;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:#F0EDF7;color:#6B5A8E;font-size:.78rem;font-weight:700;">{{ $m->children_count }}</span>
                            </td>
                        @endif
                        <td style="padding:.6rem .75rem;">
                            @if(strtoupper($m->status)==='ACTIVE')
                                <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .6rem;border-radius:6px;background:#d1fae518;color:#065f46;font-size:.76rem;font-weight:600;border:1px solid #6ee7b722;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span>Aktif
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .6rem;border-radius:6px;background:#fee2e218;color:#991b1b;font-size:.76rem;font-weight:600;border:1px solid #fca5a522;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#ef4444;"></span>Ditangguhkan
                                </span>
                            @endif
                        </td>
                        <td style="padding:.6rem .75rem;color:#9ca3af;font-size:.8rem;">{{ $m->created_at->format('d M Y') }}</td>
                        <td style="padding:.6rem .75rem;">
                            <a href="{{ route('superadmin.members.show', $m) }}"
                               style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;background:#F0EDF7;color:#6B5A8E;text-decoration:none;transition:background .15s;"
                               onmouseover="this.style.background='#8E77AB';this.style.color='#fff'"
                               onmouseout="this.style.background='#F0EDF7';this.style.color='#6B5A8E'"
                               title="Detail">
                                <i class="ti ti-eye" style="font-size:.9rem;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:4rem 1rem;color:#9ca3af;">
                            <i class="ti ti-users" style="font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;"></i>
                            <div style="font-size:.9rem;">Tidak ada pengguna ditemukan.</div>
                            @if(request('search') || request('status'))
                            <a href="{{ route('superadmin.members.index',['tab'=>$tab]) }}" style="font-size:.82rem;color:#8E77AB;margin-top:.5rem;display:inline-block;">Hapus filter</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div style="font-size:.8rem;color:#9ca3af;">
                Menampilkan {{ $members->firstItem() ?? 0 }}–{{ $members->lastItem() ?? 0 }} dari {{ $members->total() }} pengguna
            </div>
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@push('styles')
<style>
/* Fix Bootstrap nav-tab active indicator if still rendered */
.nav-tabs .nav-link.active { background:#fff; border-color: rgba(142,119,171,.2) rgba(142,119,171,.2) #fff; }
</style>
@endpush
@endsection
