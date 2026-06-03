@extends('superadmin.layout.master')
@section('title', 'Buat Akun Organisasi')
@section('content')
<div class="page-header"><div class="page-block"><div class="row align-items-center"><div class="col-md-12">
  <div class="page-header-title"><h5 class="m-b-10">Buat Akun Organisasi / Sekolah</h5></div>
  <ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('superadmin.organizations.index') }}">Organisasi</a></li>
    <li class="breadcrumb-item active">Buat Baru</li>
  </ul>
</div></div></div></div>
<div class="card" style="max-width:680px;">
  <div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="alert alert-info small mb-4">
      <i class="ti ti-info-circle me-1"></i>
      Akun dengan role <strong>Organisasi</strong> hanya dapat dibuat oleh Superadmin. Pengguna organisasi dapat menambahkan anak sebanyak kuota yang ditetapkan.
    </div>
    <form action="{{ route('superadmin.organizations.store') }}" method="POST">
      @csrf
      <h6 class="fw-semibold mb-3">Informasi Akun</h6>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nama PIC / Admin <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">No. Telepon</label>
          <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Password <span class="text-danger">*</span></label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>
      </div>

      <h6 class="fw-semibold mb-3 mt-2">Informasi Organisasi</h6>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nama Organisasi / Sekolah <span class="text-danger">*</span></label>
          <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name') }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Tipe Organisasi <span class="text-danger">*</span></label>
          <select name="organization_type" class="form-select" required>
            <option value="">— Pilih Tipe —</option>
            @foreach(['Sekolah Dasar (SD)','Sekolah Luar Biasa (SLB)','Yayasan Pendidikan','Komunitas','Klinik / Terapi','Lainnya'] as $type)
              <option value="{{ $type }}" {{ old('organization_type')===$type?'selected':'' }}>{{ $type }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Kuota Anak <span class="text-danger">*</span></label>
          <input type="number" name="child_quota" class="form-control" value="{{ old('child_quota', 30) }}" min="1" max="500" required>
          <div class="form-text">Jumlah maksimal data anak yang dapat ditambahkan.</div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary">Buat Akun Organisasi</button>
        <a href="{{ route('superadmin.organizations.index') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
