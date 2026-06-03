@extends('superadmin.layout.master')
@section('title', 'Fakta Unik')
@section('content')
<div class="page-header"><div class="page-block"><div class="row align-items-center"><div class="col-md-12">
  <div class="page-header-title"><h5 class="m-b-10">Fakta Unik Identifikasi ABK</h5></div>
  <ul class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('superadmin.landing.index') }}">Landing Content</a></li><li class="breadcrumb-item active">Fakta Unik</li></ul>
</div></div></div></div>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Fakta Unik</h5>
    <a href="{{ route('superadmin.landing.facts.create') }}" class="btn btn-sm btn-primary"><i class="ti ti-plus me-1"></i>Tambah Fakta</a>
  </div>
  <div class="card-body">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive">
      <table class="table table-hover">
        <thead><tr><th>#</th><th>Judul</th><th>Icon</th><th>Urutan</th><th>Aktif</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($facts as $fact)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $fact->title }}<br><small class="text-muted">{{ Str::limit($fact->body, 60) }}</small></td>
            <td><code>{{ $fact->icon ?? '—' }}</code></td>
            <td>{{ $fact->sort_order }}</td>
            <td>{!! $fact->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' !!}</td>
            <td>
              <a href="{{ route('superadmin.landing.facts.edit', $fact) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-edit"></i></a>
              <form action="{{ route('superadmin.landing.facts.destroy', $fact) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus fakta ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted py-4">Belum ada fakta. <a href="{{ route('superadmin.landing.facts.create') }}">Tambah sekarang</a>.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
