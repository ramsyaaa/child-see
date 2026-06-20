@extends('superadmin.layout.master')
@section('title', 'Akun Organisasi')
@section('content')
<div class="page-header"><div class="page-block"><div class="row align-items-center"><div class="col-md-12">
  <div class="page-header-title"><h5 class="m-b-10">Manajemen Akun Organisasi</h5></div>
  <ul class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li><li class="breadcrumb-item active">Organisasi</li></ul>
</div></div></div></div>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Akun Organisasi / Sekolah</h5>
    <a href="{{ route('superadmin.organizations.create') }}" class="btn btn-sm btn-primary"><i class="ti ti-plus me-1"></i>Buat Akun Organisasi</a>
  </div>
  <div class="card-body">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form class="row g-2 mb-3" method="GET">
      <div class="col-md-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama / email / organisasi..." value="{{ request('search') }}"></div>
      <div class="col-auto"><button class="btn btn-sm btn-outline-secondary">Cari</button></div>
    </form>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead><tr><th>#</th><th>Nama Akun</th><th>Organisasi</th><th>Tipe</th><th>Kuota Anak</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($organizations as $org)
          <tr>
            <td>{{ $organizations->firstItem() + $loop->index }}</td>
            <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $org->name }} ({{ $org->email }})">{{ $org->name }}<br><small class="text-muted">{{ $org->email }}</small></td>
            <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $org->organization_name }}">{{ $org->organization_name ?? '—' }}</td>
            <td><span class="badge bg-light text-dark">{{ $org->organization_type ?? '—' }}</span></td>
            <td>
              <span class="badge" style="background:#8E77AB;color:#fff;">{{ $org->child_quota }} anak</span>
              <a href="{{ route('superadmin.organizations.quota', $org) }}" class="btn btn-sm btn-outline-secondary ms-1" title="Ubah kuota"><i class="ti ti-edit"></i></a>
            </td>
            <td>
              @if(strtolower($org->status) === 'active')
                <span class="badge bg-success">Aktif</span>
              @else
                <span class="badge bg-secondary">{{ ucfirst($org->status) }}</span>
              @endif
            </td>
            <td><a href="{{ route('superadmin.members.edit', $org) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-edit"></i></a></td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center text-muted py-4">Belum ada akun organisasi. <a href="{{ route('superadmin.organizations.create') }}">Buat sekarang</a>.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $organizations->links() }}
  </div>
</div>
@endsection
