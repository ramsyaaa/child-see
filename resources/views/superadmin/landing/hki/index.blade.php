@extends('superadmin.layout.master')
@section('title', 'HKI Paten')
@section('content')
<div class="page-header"><div class="page-block"><div class="row align-items-center"><div class="col-md-12">
  <div class="page-header-title"><h5 class="m-b-10">Info HKI / Paten</h5></div>
  <ul class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('superadmin.landing.index') }}">Landing Content</a></li><li class="breadcrumb-item active">HKI Paten</li></ul>
</div></div></div></div>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar HKI / Paten</h5>
    <a href="{{ route('superadmin.landing.hki.create') }}" class="btn btn-sm btn-primary"><i class="ti ti-plus me-1"></i>Tambah HKI</a>
  </div>
  <div class="card-body">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive">
      <table class="table table-hover">
        <thead><tr><th>#</th><th>Gambar</th><th>Judul</th><th>No. Sertifikat</th><th>Tanggal</th><th>Aktif</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($hkis as $h)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>@if($h->image)<img src="{{ asset('storage/'.$h->image) }}" width="60" class="rounded">@else<span class="text-muted">—</span>@endif</td>
            <td>{{ $h->title }}<br><small class="text-muted">{{ Str::limit($h->description, 60) }}</small></td>
            <td>{{ $h->certificate_number ?? '—' }}</td>
            <td>{{ $h->issued_date ? $h->issued_date->format('d/m/Y') : '—' }}</td>
            <td>{!! $h->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' !!}</td>
            <td>
              <a href="{{ route('superadmin.landing.hki.edit', $h) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-edit"></i></a>
              <form action="{{ route('superadmin.landing.hki.destroy', $h) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus HKI ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data HKI.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
