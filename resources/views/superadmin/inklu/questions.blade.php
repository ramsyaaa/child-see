@extends('superadmin.layout.master')
@section('page-title', 'Master Pertanyaan')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.dashboard') }}">InkluSyncID</a></li>
            <li class="active">Master Pertanyaan</li>
        </ul>
        <h2>Master Pertanyaan</h2>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Type tabs -->
<div class="d-flex gap-2 mb-3">
    @foreach(['penglihatan' => 'Penglihatan', 'pendengaran' => 'Pendengaran', 'intelektual' => 'Intelektual'] as $t => $label)
    <a href="{{ route('superadmin.inklu.questions.index', ['type' => $t]) }}"
       style="padding:0.4rem 1rem;border-radius:2rem;font-size:0.85rem;text-decoration:none;{{ $type === $t ? 'background:#5C477F;color:#fff;font-weight:600;' : 'background:#E9E9EB;color:#4A3769;' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="row g-3">
    <!-- Questions list -->
    <div class="col-md-8">
        <div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                <h6 class="mb-0" style="color:#4A3769;">{{ $questions->count() }} pertanyaan — {{ ['penglihatan'=>'Hambatan Penglihatan','pendengaran'=>'Hambatan Pendengaran','intelektual'=>'Hambatan Intelektual'][$type] }}</h6>
            </div>
            <div class="card-body p-0">
                @forelse($questions as $q)
                <div class="d-flex align-items-start gap-3 p-3" style="border-bottom:1px solid rgba(186,166,214,0.15);">
                    <span style="font-size:0.75rem;color:rgba(74,55,105,0.40);font-family:monospace;padding-top:2px;min-width:28px;">{{ str_pad($q->sort_order,2,'0',STR_PAD_LEFT) }}</span>
                    <div class="flex-grow-1">
                        <span style="color:{{ $q->active ? '#26223A' : 'rgba(38,34,58,0.35)' }};font-size:0.9rem;{{ !$q->active ? 'text-decoration:line-through;' : '' }}">{{ $q->body }}</span>
                    </div>
                    <div class="d-flex gap-2 flex-shrink-0">
                        <button onclick="openEdit({{ $q->id }}, '{{ addslashes($q->body) }}', {{ $q->sort_order }}, {{ $q->active ? 1 : 0 }})"
                                style="background:rgba(92,71,127,0.10);border:none;color:#5C477F;padding:3px 10px;border-radius:4px;font-size:0.75rem;cursor:pointer;">
                            Edit
                        </button>
                        <form action="{{ route('superadmin.inklu.questions.destroy', $q) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini?')">
                            @csrf @method('DELETE')
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button type="submit" style="background:rgba(192,57,43,0.08);border:none;color:#c0392b;padding:3px 10px;border-radius:4px;font-size:0.75rem;cursor:pointer;">Hapus</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-4" style="color:rgba(74,55,105,0.40);">Belum ada pertanyaan untuk jenis ini.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Add form -->
    <div class="col-md-4">
        <div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
            <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                <h6 class="mb-0" style="color:#4A3769;">Tambah Pertanyaan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.inklu.questions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="mb-3">
                        <label style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Butir Pernyataan</label>
                        <textarea name="body" rows="4" placeholder="Anak sering…" class="form-control mt-1" style="border-color:#BAA6D6;font-size:0.875rem;" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Urutan (opsional)</label>
                        <input name="sort_order" type="number" min="0" class="form-control form-control-sm mt-1" style="border-color:#BAA6D6;" placeholder="biarkan kosong = otomatis">
                    </div>
                    <button type="submit" class="btn w-100" style="background:#5C477F;color:#fff;font-size:0.875rem;">Tambah Pertanyaan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border:1.5px solid rgba(186,166,214,0.30);">
            <div class="modal-header" style="border-bottom:1px solid rgba(186,166,214,0.2);">
                <h5 class="modal-title" style="color:#4A3769;font-family:'Playfair Display SC',serif;font-size:1.1rem;">Edit Pertanyaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Butir Pernyataan</label>
                        <textarea id="editBody" name="body" rows="4" class="form-control mt-1" style="border-color:#BAA6D6;" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Urutan</label>
                        <input id="editOrder" name="sort_order" type="number" min="0" class="form-control form-control-sm mt-1" style="border-color:#BAA6D6;">
                    </div>
                    <div class="form-check">
                        <input id="editActive" type="checkbox" name="active" value="1" class="form-check-input">
                        <label for="editActive" class="form-check-label" style="font-size:0.875rem;">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid rgba(186,166,214,0.2);">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm" style="background:#5C477F;color:#fff;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEdit(id, body, order, active) {
    document.getElementById('editForm').action = `/superadmin/inklu/questions/${id}`;
    document.getElementById('editBody').value = body;
    document.getElementById('editOrder').value = order;
    document.getElementById('editActive').checked = active == 1;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endpush
@endsection
