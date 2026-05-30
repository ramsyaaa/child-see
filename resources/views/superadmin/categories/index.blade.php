@extends('superadmin.layout.master')
@section('page-title', 'Kategori ABK')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li class="active">Kategori ABK</li>
        </ul>
        <h2>Kategori ABK</h2>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.categories.create') }}" class="btn-banner"><i class="ti ti-plus"></i> Tambah Kategori</a>
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

<div class="card">
    <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Daftar Kategori</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Tipe</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($cat->icon)
                                <i class="ti ti-{{ $cat->icon }} me-1" style="color:{{ $cat->color ?? '#4A3769' }};"></i>
                            @endif
                            <strong>{{ $cat->name }}</strong>
                        </td>
                        <td><code class="text-muted small">{{ $cat->slug }}</code></td>
                        <td><span class="badge bg-light text-dark border">{{ $cat->type }}</span></td>
                        <td>{{ $cat->sort_order }}</td>
                        <td>
                            @if($cat->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('superadmin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('superadmin.categories.destroy', $cat) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Hapus kategori ini?')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ti ti-folder-off" style="font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;"></i>
                            Belum ada kategori.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection