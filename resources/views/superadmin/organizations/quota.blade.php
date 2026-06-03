@extends('superadmin.layout.master')
@section('title', 'Ubah Kuota Anak')
@section('content')
<div class="page-header"><div class="page-block"><div class="row align-items-center"><div class="col-md-12">
  <div class="page-header-title"><h5 class="m-b-10">Ubah Kuota Data Anak</h5></div>
  <ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('superadmin.organizations.index') }}">Organisasi</a></li>
    <li class="breadcrumb-item active">Ubah Kuota</li>
  </ul>
</div></div></div></div>
<div class="card" style="max-width:480px;">
  <div class="card-body">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="mb-4 p-3 rounded" style="background:#8E77AB15;border:1px solid #8E77AB33;">
      <div class="fw-semibold">{{ $member->name }}</div>
      <div class="text-muted small">{{ $member->email }}</div>
      @if($member->organization_name)
        <div class="small mt-1"><i class="ti ti-building me-1" style="color:#8E77AB;"></i>{{ $member->organization_name }}</div>
      @endif
      <div class="mt-2">
        <span class="badge" style="background:#8E77AB;color:#fff;">{{ $member->getRoleLabel() }}</span>
        <span class="badge bg-light text-dark ms-1">Kuota saat ini: {{ $member->child_quota }} anak</span>
      </div>
    </div>
    <form action="{{ route('superadmin.organizations.quota.update', $member) }}" method="POST">
      @csrf @method('PUT')
      <div class="mb-3">
        <label class="form-label fw-semibold">Kuota Baru</label>
        <input type="number" name="child_quota" class="form-control" value="{{ old('child_quota', $member->child_quota) }}" min="1" max="500" required>
        <div class="form-text">Nilai default untuk akun Orang Tua / Guru adalah 1. Organisasi biasanya mendapat kuota lebih besar.</div>
      </div>
      @if($errors->any())<div class="alert alert-danger small">{{ $errors->first() }}</div>@endif
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan Kuota</button>
        <a href="{{ route('superadmin.organizations.index') }}" class="btn btn-outline-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>
@endsection
