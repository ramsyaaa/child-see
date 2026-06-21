@extends('superadmin.layout.master')
@section('page-title', 'Edit Kategori')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.categories.index') }}">Kategori ABK</a></li>
            <li class="active">Edit Kategori</li>
        </ul>
        <h2>Edit Kategori</h2>
    </div>
</div>
@endsection

@section('content')

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="ti ti-alert-circle me-2"></i><strong>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Edit: {{ $category->name }}</h5></div>
    <div class="card-body">
        <form action="{{ route('superadmin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="inp-name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $category->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Slug</label>
                    <input type="text" name="slug" id="inp-slug" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug', $category->slug) }}">
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="intelektual" @selected(old('type', $category->type)=='intelektual')>Intelektual</option>
                        <option value="sensorik"    @selected(old('type', $category->type)=='sensorik')>Sensorik</option>
                        <option value="akademik"    @selected(old('type', $category->type)=='akademik')>Akademik / Belajar</option>
                        <option value="fisik"       @selected(old('type', $category->type)=='fisik')>Fisik</option>
                        <option value="mental"      @selected(old('type', $category->type)=='mental')>Mental</option>
                        <option value="majemuk"     @selected(old('type', $category->type)=='majemuk')>Majemuk</option>
                        <option value="kombinasi"   @selected(old('type', $category->type)=='kombinasi')>Kombinasi</option>
                        <option value="emosional"   @selected(old('type', $category->type)=='emosional')>Emosional</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Grup Tampilan</label>
                    <select name="group" class="form-select @error('group') is-invalid @enderror">
                        <option value="">-- Tanpa Grup --</option>
                        <option value="fisik"                @selected(old('group', $category->group)=='fisik')>Disabilitas Fisik</option>
                        <option value="intelektual_genetik"  @selected(old('group', $category->group)=='intelektual_genetik')>Intelektual — Nampak Fisik (Genetik)</option>
                        <option value="intelektual"          @selected(old('group', $category->group)=='intelektual')>Disabilitas Intelektual</option>
                        <option value="hambatan_belajar"     @selected(old('group', $category->group)=='hambatan_belajar')>Hambatan Belajar Spesifik</option>
                        <option value="sensorik_penglihatan" @selected(old('group', $category->group)=='sensorik_penglihatan')>Sensorik — Penglihatan</option>
                        <option value="sensorik_pendengaran" @selected(old('group', $category->group)=='sensorik_pendengaran')>Sensorik — Pendengaran</option>
                        <option value="sensorik_wicara"      @selected(old('group', $category->group)=='sensorik_wicara')>Sensorik — Wicara</option>
                        <option value="mental_autism"        @selected(old('group', $category->group)=='mental_autism')>Mental — Spektrum Autisme</option>
                        <option value="mental_adhd"          @selected(old('group', $category->group)=='mental_adhd')>Mental — ADHD</option>
                        <option value="mental_emosi"         @selected(old('group', $category->group)=='mental_emosi')>Mental — Emosi & Perilaku</option>
                        <option value="majemuk"              @selected(old('group', $category->group)=='majemuk')>Disabilitas Majemuk</option>
                    </select>
                    @error('group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Ikon</label>
                    <select name="icon" class="form-select @error('icon') is-invalid @enderror">
                        <option value="">-- Pilih Ikon --</option>
                        <option value="brain"         @selected(old('icon', $category->icon)=='brain')>Brain (Otak)</option>
                        <option value="eye"           @selected(old('icon', $category->icon)=='eye')>Eye (Mata)</option>
                        <option value="book"          @selected(old('icon', $category->icon)=='book')>Book (Buku)</option>
                        <option value="clock"         @selected(old('icon', $category->icon)=='clock')>Clock (Jam)</option>
                        <option value="heart"         @selected(old('icon', $category->icon)=='heart')>Heart (Hati)</option>
                        <option value="dna"           @selected(old('icon', $category->icon)=='dna')>DNA (Genetik)</option>
                        <option value="accessibility" @selected(old('icon', $category->icon)=='accessibility')>Accessibility (Fisik)</option>
                        <option value="ear"           @selected(old('icon', $category->icon)=='ear')>Ear (Pendengaran)</option>
                        <option value="message"       @selected(old('icon', $category->icon)=='message')>Message (Wicara)</option>
                        <option value="puzzle"        @selected(old('icon', $category->icon)=='puzzle')>Puzzle (Autisme)</option>
                        <option value="zap"           @selected(old('icon', $category->icon)=='zap')>Zap (ADHD)</option>
                        <option value="pencil"        @selected(old('icon', $category->icon)=='pencil')>Pencil (Menulis)</option>
                        <option value="calculator"    @selected(old('icon', $category->icon)=='calculator')>Calculator (Matematika)</option>
                        <option value="layers"        @selected(old('icon', $category->icon)=='layers')>Layers (Majemuk)</option>
                        <option value="star"          @selected(old('icon', $category->icon)=='star')>Star (Berbakat)</option>
                    </select>
                    @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Warna</label>
                    <input type="color" name="color" class="form-control form-control-color w-100 @error('color') is-invalid @enderror"
                        value="{{ old('color', $category->color ?? '#4A3769') }}">
                    @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Ilustrasi Hasil Asesmen</label>
                    @if($category->result_illustration)
                        <div class="mb-2 d-flex align-items-center gap-3">
                            <img src="{{ asset($category->result_illustration) }}" alt="Ilustrasi"
                                 style="height:80px;border-radius:8px;object-fit:cover;border:1px solid rgba(142,119,171,.2);">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_illustration" id="remove_illustration" value="1">
                                <label class="form-check-label small text-danger" for="remove_illustration">Hapus gambar ini</label>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="result_illustration_file" accept="image/*"
                        class="form-control @error('result_illustration_file') is-invalid @enderror">
                    <small class="text-muted">Maks. 5MB (otomatis dikompres ke ±600KB). Biarkan kosong untuk tidak mengubah gambar.</small>
                    @error('result_illustration_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                        value="{{ old('sort_order', $category->sort_order) }}" min="0">
                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            @checked(old('is_active', $category->is_active))>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Perbarui
                    </button>
                    <a href="{{ route('superadmin.categories.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection