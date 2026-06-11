@extends('superadmin.layout.master')
@section('page-title', 'Tambah Kategori')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.categories.index') }}">Kategori ABK</a></li>
            <li class="active">Tambah Kategori</li>
        </ul>
        <h2>Tambah Kategori</h2>
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
    <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Form Kategori Baru</h5></div>
    <div class="card-body">
        <form action="{{ route('superadmin.categories.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="inp-name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="mis. Gangguan Intelektual" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Slug</label>
                    <input type="text" name="slug" id="inp-slug" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug') }}" placeholder="auto-generate dari nama">
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Kosongkan untuk otomatis dibuat dari nama.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="intelektual" @selected(old('type')=='intelektual')>Intelektual</option>
                        <option value="sensorik"    @selected(old('type')=='sensorik')>Sensorik</option>
                        <option value="akademik"    @selected(old('type')=='akademik')>Akademik / Belajar</option>
                        <option value="fisik"       @selected(old('type')=='fisik')>Fisik</option>
                        <option value="mental"      @selected(old('type')=='mental')>Mental</option>
                        <option value="majemuk"     @selected(old('type')=='majemuk')>Majemuk</option>
                        <option value="kombinasi"   @selected(old('type')=='kombinasi')>Kombinasi</option>
                        <option value="emosional"   @selected(old('type')=='emosional')>Emosional</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Grup Tampilan</label>
                    <select name="group" class="form-select @error('group') is-invalid @enderror">
                        <option value="">-- Tanpa Grup --</option>
                        <option value="fisik"                @selected(old('group')=='fisik')>Disabilitas Fisik</option>
                        <option value="intelektual_genetik"  @selected(old('group')=='intelektual_genetik')>Intelektual — Nampak Fisik (Genetik)</option>
                        <option value="intelektual"          @selected(old('group')=='intelektual')>Disabilitas Intelektual</option>
                        <option value="hambatan_belajar"     @selected(old('group')=='hambatan_belajar')>Hambatan Belajar Spesifik</option>
                        <option value="sensorik_penglihatan" @selected(old('group')=='sensorik_penglihatan')>Sensorik — Penglihatan</option>
                        <option value="sensorik_pendengaran" @selected(old('group')=='sensorik_pendengaran')>Sensorik — Pendengaran</option>
                        <option value="sensorik_wicara"      @selected(old('group')=='sensorik_wicara')>Sensorik — Wicara</option>
                        <option value="mental_autism"        @selected(old('group')=='mental_autism')>Mental — Spektrum Autisme</option>
                        <option value="mental_adhd"          @selected(old('group')=='mental_adhd')>Mental — ADHD</option>
                        <option value="mental_emosi"         @selected(old('group')=='mental_emosi')>Mental — Emosi & Perilaku</option>
                        <option value="majemuk"              @selected(old('group')=='majemuk')>Disabilitas Majemuk</option>
                    </select>
                    @error('group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Ikon</label>
                    <select name="icon" class="form-select @error('icon') is-invalid @enderror">
                        <option value="">-- Pilih Ikon --</option>
                        <option value="brain"         @selected(old('icon')=='brain')>Brain (Otak)</option>
                        <option value="eye"           @selected(old('icon')=='eye')>Eye (Mata)</option>
                        <option value="book"          @selected(old('icon')=='book')>Book (Buku)</option>
                        <option value="clock"         @selected(old('icon')=='clock')>Clock (Jam)</option>
                        <option value="heart"         @selected(old('icon')=='heart')>Heart (Hati)</option>
                        <option value="dna"           @selected(old('icon')=='dna')>DNA (Genetik)</option>
                        <option value="accessibility" @selected(old('icon')=='accessibility')>Accessibility (Fisik)</option>
                        <option value="ear"           @selected(old('icon')=='ear')>Ear (Pendengaran)</option>
                        <option value="message"       @selected(old('icon')=='message')>Message (Wicara)</option>
                        <option value="puzzle"        @selected(old('icon')=='puzzle')>Puzzle (Autisme)</option>
                        <option value="zap"           @selected(old('icon')=='zap')>Zap (ADHD)</option>
                        <option value="pencil"        @selected(old('icon')=='pencil')>Pencil (Menulis)</option>
                        <option value="calculator"    @selected(old('icon')=='calculator')>Calculator (Matematika)</option>
                        <option value="layers"        @selected(old('icon')=='layers')>Layers (Majemuk)</option>
                        <option value="star"          @selected(old('icon')=='star')>Star (Berbakat)</option>
                    </select>
                    @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Warna</label>
                    <input type="color" name="color" class="form-control form-control-color w-100 @error('color') is-invalid @enderror"
                        value="{{ old('color', '#4A3769') }}">
                    @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                        placeholder="Deskripsi singkat kategori ini...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Ilustrasi Hasil Asesmen</label>
                    <input type="text" name="result_illustration" class="form-control @error('result_illustration') is-invalid @enderror"
                        value="{{ old('result_illustration') }}"
                        placeholder="assets/img/hasil-analisa/NamaFile.jpg">
                    <small class="text-muted">Path relatif dari <code>public/</code>. Kosongkan jika tidak ada gambar.</small>
                    @error('result_illustration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                        value="{{ old('sort_order', 0) }}" min="0">
                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            @checked(old('is_active', true))>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan
                    </button>
                    <a href="{{ route('superadmin.categories.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('inp-name').addEventListener('input', function () {
    const slug = document.getElementById('inp-slug');
    if (slug.dataset.manual) return;
    slug.value = this.value.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .trim().replace(/\s+/g, '-');
});
document.getElementById('inp-slug').addEventListener('input', function () {
    this.dataset.manual = '1';
});
</script>
@endsection