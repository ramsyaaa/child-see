@extends('superadmin.layout.master')
@section('title', 'Partner')
@section('content')
<div class="page-header"><div class="page-block"><div class="row align-items-center"><div class="col-md-12">
  <div class="page-header-title"><h5 class="m-b-10">Partner Pusat Tumbuh Kembang</h5></div>
  <ul class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('superadmin.landing.index') }}">Landing Content</a></li><li class="breadcrumb-item active">Partner</li></ul>
</div></div></div></div>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Partner</h5>
    <a href="{{ route('superadmin.landing.partners.create') }}" class="btn btn-sm btn-primary"><i class="ti ti-plus me-1"></i>Tambah Partner</a>
  </div>
  <div class="card-body">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive">
      <table class="table table-hover">
        <thead><tr><th>#</th><th>Logo</th><th>Nama</th><th>Website</th><th>Urutan</th><th>Aktif</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($partners as $p)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>@if($p->logo)<img src="{{ asset('storage/'.$p->logo) }}" height="40" class="rounded">@else<span class="text-muted">—</span>@endif</td>
            <td>{{ $p->name }}<br><small class="text-muted">{{ Str::limit($p->description, 50) }}</small></td>
            <td>@if($p->website_url)<a href="{{ $p->website_url }}" target="_blank" class="text-primary">{{ Str::limit($p->website_url, 30) }}</a>@else<span class="text-muted">—</span>@endif</td>
            <td>{{ $p->sort_order }}</td>
            <td>{!! $p->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' !!}</td>
            <td>
              <a href="{{ route('superadmin.landing.partners.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-edit"></i></a>
              <form action="{{ route('superadmin.landing.partners.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus partner ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center text-muted py-4">Belum ada partner.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
