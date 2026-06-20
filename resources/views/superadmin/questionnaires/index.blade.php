@extends('superadmin.layout.master')
@section('page-title', 'Pertanyaan Asesmen')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li class="active">Pertanyaan Asesmen</li>
        </ul>
        <h2>Pertanyaan Asesmen</h2>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.questionnaires.create') }}" class="btn-banner"><i class="ti ti-plus"></i> Tambah Pertanyaan</a>
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

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Kategori</label>
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Domain</label>
                <select name="domain_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Domain</option>
                    @foreach($domains ?? [] as $dom)
                        <option value="{{ $dom->id }}" @selected(request('domain_id') == $dom->id)>{{ $dom->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('superadmin.questionnaires.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Daftar Pertanyaan</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pertanyaan</th>
                        <th>Kategori</th>
                        <th>Domain</th>
                        <th>Tipe</th>
                        <th>Bobot</th>
                        <th>Urutan</th>
                        <th>Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questionnaires as $q)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="max-width:280px;white-space:normal;overflow-wrap:break-word;word-break:break-word;">
                            <span title="{{ $q->question }}">{{ \Illuminate\Support\Str::limit($q->question, 70) }}</span>
                        </td>
                        <td>
                            @if($q->category)
                                <span class="badge bg-light text-dark border">{{ $q->category->name }}</span>
                            @else<span class="text-muted">—</span>@endif
                        </td>
                        <td>
                            @if($q->domain)
                                <span class="badge" style="background:#BAA6D6;color:#2E2046;">{{ $q->domain->name }}</span>
                            @else<span class="text-muted">—</span>@endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $q->question_type }}</span></td>
                        <td>{{ $q->weight }}</td>
                        <td>{{ $q->sort_order }}</td>
                        <td>
                            @if($q->is_active)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('superadmin.questionnaires.edit', $q) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('superadmin.questionnaires.destroy', $q) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Hapus pertanyaan ini?')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="ti ti-question-mark" style="font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;"></i>
                            Belum ada pertanyaan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection