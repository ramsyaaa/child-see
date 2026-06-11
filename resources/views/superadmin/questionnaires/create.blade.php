@extends('superadmin.layout.master')
@section('page-title', 'Tambah Pertanyaan')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.questionnaires.index') }}">Pertanyaan Asesmen</a></li>
            <li class="active">Tambah Pertanyaan</li>
        </ul>
        <h2>Tambah Pertanyaan</h2>
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

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Form Pertanyaan Baru</h5></div>
            <div class="card-body">
                <form action="{{ route('superadmin.questionnaires.store') }}" method="POST" id="q-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="sel-category" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Domain <span class="text-danger">*</span></label>
                            <select name="domain_id" id="sel-domain" class="form-select @error('domain_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Domain --</option>
                                @foreach($domains as $dom)
                                    <option value="{{ $dom->id }}"
                                        data-category="{{ $dom->category_id }}"
                                        @selected(old('domain_id') == $dom->id)>{{ $dom->name }}</option>
                                @endforeach
                            </select>
                            @error('domain_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                            <textarea name="question" rows="4" class="form-control @error('question') is-invalid @enderror"
                                placeholder="Tulis pertanyaan observasi..." required>{{ old('question') }}</textarea>
                            @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipe Pertanyaan <span class="text-danger">*</span></label>
                            <select name="question_type" id="sel-type" class="form-select @error('question_type') is-invalid @enderror" required>
                                <option value="yes_no" @selected(old('question_type','yes_no')=='yes_no')>Ya/Tidak (Skala 3)</option>
                                <option value="scale"  @selected(old('question_type')=='scale')>Skala</option>
                                <option value="text"   @selected(old('question_type')=='text')>Teks Bebas</option>
                            </select>
                            @error('question_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Bobot</label>
                            <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror"
                                value="{{ old('weight', 1) }}" min="1" step="0.01">
                            @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
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
                            <a href="{{ route('superadmin.questionnaires.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Answer Options Panel --}}
    <div class="col-lg-4">
        <div class="card" id="panel-yn" style="display:block;">
            <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Pratinjau Pilihan Jawaban</h5></div>
            <div class="card-body">
                <p class="text-muted small mb-3">Tipe <strong>Ya/Tidak</strong> menggunakan 3 opsi standar:</p>
                <div class="d-flex flex-column gap-2">
                    <div class="border rounded p-2 d-flex justify-content-between align-items-center">
                        <span>Tidak</span><span class="badge bg-success">0</span>
                    </div>
                    <div class="border rounded p-2 d-flex justify-content-between align-items-center">
                        <span>Ragu-Ragu</span><span class="badge bg-warning text-dark">1</span>
                    </div>
                    <div class="border rounded p-2 d-flex justify-content-between align-items-center">
                        <span>Iya</span><span class="badge bg-danger">2</span>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">Pilihan ini ditentukan sistem secara otomatis.</small>
            </div>
        </div>
        <div class="card" id="panel-custom" style="display:none;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color:#4A3769;">Pilihan Jawaban</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addOption()">
                    <i class="ti ti-plus me-1"></i>Tambah
                </button>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Tambahkan opsi jawaban beserta skor masing-masing.</p>
                <div id="options-container" class="d-flex flex-column gap-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    // Category → Domain filter
    const catSel = document.getElementById('sel-category');
    const domSel = document.getElementById('sel-domain');

    function filterDomains() {
        const catId = catSel.value;
        Array.from(domSel.options).forEach((o, i) => {
            if (i === 0) return;
            o.style.display = (!catId || o.dataset.category === catId) ? '' : 'none';
        });
        if (domSel.value && domSel.options[domSel.selectedIndex].style.display === 'none') {
            domSel.value = '';
        }
    }
    catSel.addEventListener('change', filterDomains);
    filterDomains();

    // Type → panel toggle
    const typeSel = document.getElementById('sel-type');
    window.togglePanel = function() {
        const isYn = typeSel.value === 'yes_no';
        document.getElementById('panel-yn').style.display = isYn ? 'block' : 'none';
        document.getElementById('panel-custom').style.display = isYn ? 'none' : 'block';
    };
    typeSel.addEventListener('change', togglePanel);
    togglePanel();
})();

var optionIndex = 0;
function addOption(label, value, score) {
    const idx = optionIndex++;
    const container = document.getElementById('options-container');
    const row = document.createElement('div');
    row.className = 'border rounded p-2';
    row.id = 'opt-row-' + idx;
    row.innerHTML = `
        <div class="d-flex gap-2 align-items-center mb-1">
            <input type="text" name="answer_options[${idx}][label]" class="form-control form-control-sm"
                   placeholder="Label (mis. Tidak Pernah)" value="${label || ''}" required>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('opt-row-${idx}').remove()">
                <i class="ti ti-trash"></i>
            </button>
        </div>
        <div class="d-flex gap-2">
            <input type="text" name="answer_options[${idx}][value]" class="form-control form-control-sm"
                   placeholder="Nilai (opsional)" value="${value || ''}">
            <input type="number" name="answer_options[${idx}][score]" class="form-control form-control-sm"
                   placeholder="Skor" value="${score !== undefined ? score : ''}" step="0.01">
        </div>`;
    container.appendChild(row);
}
</script>

@endsection