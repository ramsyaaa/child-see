@extends('superadmin.layout.master')
@section('title', isset($fact) ? 'Edit Fakta' : 'Tambah Fakta')
@section('content')
<div class="page-header"><div class="page-block"><div class="row align-items-center"><div class="col-md-12">
  <div class="page-header-title"><h5 class="m-b-10">{{ isset($fact) ? 'Edit' : 'Tambah' }} Fakta Unik</h5></div>
  <ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('superadmin.landing.index') }}">Landing Content</a></li>
    <li class="breadcrumb-item"><a href="{{ route('superadmin.landing.facts') }}">Fakta Unik</a></li>
    <li class="breadcrumb-item active">{{ isset($fact) ? 'Edit' : 'Tambah' }}</li>
  </ul>
</div></div></div></div>
<div class="card" style="max-width:640px;">
  <div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ isset($fact) ? route('superadmin.landing.facts.update', $fact) : route('superadmin.landing.facts.store') }}" method="POST">
      @csrf
      @if(isset($fact)) @method('PUT') @endif
      <div class="mb-3">
        <label class="form-label">Judul <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $fact->title ?? '') }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="body" class="form-control" rows="4">{{ old('body', $fact->body ?? '') }}</textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Icon (Tabler class, mis: ti-bulb)</label>
          <input type="text" name="icon" class="form-control" value="{{ old('icon', $fact->icon ?? '') }}" placeholder="ti-bulb">
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Urutan</label>
          <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $fact->sort_order ?? 0) }}">
        </div>
        <div class="col-md-3 mb-3 d-flex align-items-end">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $fact->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">Aktif</label>
          </div>
        </div>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('superadmin.landing.facts') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
