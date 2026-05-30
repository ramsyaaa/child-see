@extends('superadmin.layout.master')
@section('page-title', 'Domain ABK')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li class="active">Domain ABK</li>
        </ul>
        <h2>Domain ABK</h2>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.domains.create') }}" class="btn-banner"><i class="ti ti-plus"></i> Tambah Domain</a>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Filter Kategori</label>
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Daftar Domain</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Domain</th>
                        <th>Kategori</th>
                        <th>Urutan</th>
                        <th>Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($domains as $domain)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $domain->name }}</strong></td>
                        <td>
                            @if($domain->category)
                                <span class="badge bg-light text-dark border">{{ $domain->category->name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $domain->sort_order }}</td>
                        <td>
                            @if($domain->is_active)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('superadmin.domains.edit', $domain) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('superadmin.domains.destroy', $domain) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Hapus domain ini?')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="ti ti-folder-off" style="font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;"></i>
                            Belum ada domain.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection