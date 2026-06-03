@extends('superadmin.layout.master')
@section('title', isset($partner) ? 'Edit Partner' : 'Tambah Partner')
@section('content')
<div class="page-header"><div class="page-block"><div class="row align-items-center"><div class="col-md-12">
  <div class="page-header-title"><h5 class="m-b-10">{{ isset($partner) ? 'Edit' : 'Tambah' }} Partner</h5></div>
  <ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('superadmin.landing.index') }}">Landing Content</a></li>
    <li class="breadcrumb-item"><a href="{{ route('superadmin.landing.partners') }}">Partner</a></li>
    <li class="breadcrumb-item active">{{ isset($partner) ? 'Edit' : 'Tambah' }}</li>
  </ul>
</div></div></div></div>
<div class="card" style="max-width:640px;">
  <div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ isset($partner) ? route('superadmin.landing.partners.update', $partner) : route('superadmin.landing.partners.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @if(isset($partner)) @method('PUT') @endif
      <div class="mb-3">
        <label class="form-label">Nama Partner <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $partner->name ?? '') }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Logo</label>
        @if(isset($partner) && $partner->logo)
          <div class="mb-2"><img src="{{ asset('storage/'.$partner->logo) }}" height="60" class="rounded"></div>
        @endif
        <input type="file" name="logo" class="form-control" accept="image/*">
      </div>
      <div class="mb-3">
        <label class="form-label">URL Website</label>
        <input type="url" name="website_url" class="form-control" value="{{ old('website_url', $partner->website_url ?? '') }}" placeholder="https://...">
      </div>
      <div class="mb-3">
        <label class="form-label">Deskripsi Singkat</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $partner->description ?? '') }}</textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Urutan</label>
          <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $partner->sort_order ?? 0) }}">
        </div>
        <div class="col-md-6 mb-3 d-flex align-items-end">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $partner->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">Aktif</label>
          </div>
        </div>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('superadmin.landing.partners') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
