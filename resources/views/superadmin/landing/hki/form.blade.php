@extends('superadmin.layout.master')
@section('title', isset($hki) ? 'Edit HKI' : 'Tambah HKI')
@section('content')
<div class="page-header"><div class="page-block"><div class="row align-items-center"><div class="col-md-12">
  <div class="page-header-title"><h5 class="m-b-10">{{ isset($hki) ? 'Edit' : 'Tambah' }} HKI / Paten</h5></div>
  <ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('superadmin.landing.index') }}">Landing Content</a></li>
    <li class="breadcrumb-item"><a href="{{ route('superadmin.landing.hki') }}">HKI Paten</a></li>
    <li class="breadcrumb-item active">{{ isset($hki) ? 'Edit' : 'Tambah' }}</li>
  </ul>
</div></div></div></div>
<div class="card" style="max-width:640px;">
  <div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ isset($hki) ? route('superadmin.landing.hki.update', $hki) : route('superadmin.landing.hki.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @if(isset($hki)) @method('PUT') @endif
      <div class="mb-3">
        <label class="form-label">Judul <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $hki->title ?? '') }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $hki->description ?? '') }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Gambar / Sertifikat</label>
        @if(isset($hki) && $hki->image)
          <div class="mb-2"><img src="{{ asset('storage/'.$hki->image) }}" width="120" class="rounded"></div>
        @endif
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nomor Sertifikat</label>
          <input type="text" name="certificate_number" class="form-control" value="{{ old('certificate_number', $hki->certificate_number ?? '') }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Tanggal Terbit</label>
          <input type="date" name="issued_date" class="form-control" value="{{ old('issued_date', isset($hki) && $hki->issued_date ? $hki->issued_date->format('Y-m-d') : '') }}">
        </div>
      </div>
      <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $hki->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label">Tampilkan di Landing Page</label>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('superadmin.landing.hki') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
