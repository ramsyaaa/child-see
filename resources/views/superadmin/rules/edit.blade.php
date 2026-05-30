@extends('superadmin.layout.master')
@section('page-title', 'Edit Aturan Penilaian')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.rules.index') }}">Aturan Penilaian</a></li>
            <li class="active">Edit Aturan</li>
        </ul>
        <h2>Edit Aturan Penilaian</h2>
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
    <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Edit: {{ $rule->label }}</h5></div>
    <div class="card-body">
        <form action="{{ route('superadmin.rules.update', $rule) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" id="sel-category" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $rule->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Domain <small class="text-muted">(kosong = keseluruhan)</small></label>
                    <select name="domain_id" id="sel-domain" class="form-select @error('domain_id') is-invalid @enderror">
                        <option value="">— Keseluruhan —</option>
                        @foreach($domains as $dom)
                            <option value="{{ $dom->id }}"
                                data-category="{{ $dom->category_id }}"
                                @selected(old('domain_id', $rule->domain_id) == $dom->id)>{{ $dom->name }}</option>
                        @endforeach
                    </select>
                    @error('domain_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
                    <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                        value="{{ old('label', $rule->label) }}" required>
                    @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tingkat Keparahan <span class="text-danger">*</span></label>
                    <select name="severity_level" id="sel-severity" class="form-select @error('severity_level') is-invalid @enderror" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="normal" @selected(old('severity_level', $rule->severity_level)=='normal')>Belum Terindikasi</option>
                        <option value="light"  @selected(old('severity_level', $rule->severity_level)=='light')>Terindikasi Ringan</option>
                        <option value="medium" @selected(old('severity_level', $rule->severity_level)=='medium')>Terindikasi Sedang</option>
                        <option value="heavy"  @selected(old('severity_level', $rule->severity_level)=='heavy')>Terindikasi Kuat</option>
                    </select>
                    @error('severity_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Skor Minimum <span class="text-danger">*</span></label>
                    <input type="number" name="min_score" class="form-control @error('min_score') is-invalid @enderror"
                        value="{{ old('min_score', $rule->min_score) }}" min="0" step="0.01" required>
                    @error('min_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Skor Maksimum <span class="text-danger">*</span></label>
                    <input type="number" name="max_score" class="form-control @error('max_score') is-invalid @enderror"
                        value="{{ old('max_score', $rule->max_score) }}" min="0" step="0.01" required>
                    @error('max_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Warna</label>
                    <input type="color" name="color" id="inp-color" class="form-control form-control-color w-100 @error('color') is-invalid @enderror"
                        value="{{ old('color', $rule->color ?? '#839986') }}">
                    @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $rule->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Rekomendasi</label>
                    <textarea name="recommendation" rows="4" class="form-control @error('recommendation') is-invalid @enderror">{{ old('recommendation', $rule->recommendation) }}</textarea>
                    @error('recommendation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Perbarui
                    </button>
                    <a href="{{ route('superadmin.rules.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const catSel = document.getElementById('sel-category');
    const domSel = document.getElementById('sel-domain');
    function filterDomains() {
        const catId = catSel.value;
        Array.from(domSel.options).forEach((o, i) => {
            if (i === 0) return;
            o.style.display = (!catId || o.dataset.category === catId) ? '' : 'none';
        });
    }
    catSel.addEventListener('change', filterDomains);
    filterDomains();

    const sevSel = document.getElementById('sel-severity');
    const colorInp = document.getElementById('inp-color');
    const sevColors = { normal:'#839986', light:'#8D77AB', medium:'#A86916', heavy:'#dc3545' };
    sevSel.addEventListener('change', function () {
        if (sevColors[this.value]) colorInp.value = sevColors[this.value];
    });
})();
</script>

@endsection