@extends('superadmin.layout.master')
@section('title', isset($member) ? 'Edit Anggota Tim' : 'Tambah Anggota Tim')
@section('content')

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.landing.index') }}">Landing Content</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.landing.team') }}">Tim Pengembang</a></li>
                    <li class="breadcrumb-item active">{{ isset($member) ? 'Edit' : 'Tambah' }}</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ isset($member) ? 'Edit' : 'Tambah' }} Anggota Tim</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li style="font-size:.85rem;">{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ isset($member) ? route('superadmin.landing.team.update', $member) : route('superadmin.landing.team.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($member)) @method('PUT') @endif

                    {{-- Avatar preview + upload --}}
                    <div class="mb-4 d-flex align-items-center gap-4">
                        <div id="avatarPreviewWrap" style="position:relative;flex-shrink:0;">
                            @php
                                $existingPhoto = isset($member) && $member->photo ? asset('storage/'.$member->photo) : null;
                                $initials = strtoupper(substr(old('name', $member->name ?? 'T'), 0, 1));
                            @endphp
                            {{-- SVG initials avatar (shown when no photo) --}}
                            <div id="avatarInitials"
                                 style="width:80px;height:80px;border-radius:16px;background:linear-gradient(135deg,#4A3769,#8E77AB);display:{{ $existingPhoto ? 'none' : 'flex' }};align-items:center;justify-content:center;color:#fff;font-size:2rem;font-weight:700;font-family:'Playfair Display',serif;letter-spacing:-.02em;">
                                {{ $initials }}
                            </div>
                            {{-- Real photo --}}
                            <img id="avatarImg"
                                 src="{{ $existingPhoto ?? '' }}"
                                 alt="foto"
                                 style="width:80px;height:80px;border-radius:16px;object-fit:cover;display:{{ $existingPhoto ? 'block' : 'none' }};border:2px solid rgba(142,119,171,.2);">
                            {{-- Camera overlay button --}}
                            <label for="photoInput" style="position:absolute;bottom:-6px;right:-6px;width:26px;height:26px;border-radius:50%;background:#8E77AB;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid #fff;" title="Ganti foto">
                                <i class="ti ti-camera" style="font-size:.75rem;color:#fff;"></i>
                            </label>
                        </div>
                        <div>
                            <p class="fw-semibold mb-1" style="font-size:.88rem;color:#2E2046;">Foto Anggota</p>
                            <p class="text-muted mb-2" style="font-size:.78rem;">JPG, PNG, WebP. Maks 2 MB. Rasio 1:1 dianjurkan.</p>
                            <label for="photoInput" class="btn btn-sm btn-outline-secondary" style="cursor:pointer;border-radius:8px;">
                                <i class="ti ti-upload me-1"></i>Pilih Foto
                            </label>
                            <button type="button" id="removePhotoBtn" onclick="removePhoto()" style="display:none;" class="btn btn-sm btn-outline-danger ms-1" style="border-radius:8px;">
                                <i class="ti ti-x me-1"></i>Hapus
                            </button>
                        </div>
                        <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none;">
                        @if(isset($member) && $member->photo)
                            <input type="hidden" name="remove_photo" id="removePhotoFlag" value="0">
                        @endif
                    </div>

                    <hr class="mb-3">

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="nameInput" class="form-control" value="{{ old('name', $member->name ?? '') }}" required placeholder="Dr. Budi Santoso, M.Pd.">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Jabatan / Role</label>
                            <input type="text" name="role_label" class="form-control" value="{{ old('role_label', $member->role_label ?? '') }}" placeholder="Dosen Pembimbing, Ketua Tim...">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Afiliasi / Institusi</label>
                            <input type="text" name="affiliation" class="form-control" value="{{ old('affiliation', $member->affiliation ?? '') }}" placeholder="Universitas XYZ">
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label fw-semibold">Grup <span class="text-danger">*</span></label>
                            <select name="group" class="form-select" required>
                                @foreach(['dosen'=>'Dosen','mahasiswa'=>'Mahasiswa','eksternal'=>'Tim Eksternal'] as $v => $l)
                                <option value="{{ $v }}" {{ old('group', $member->group ?? '') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $member->sort_order ?? 0) }}" min="0">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold">Tampilkan di Landing Page</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
                        <a href="{{ route('superadmin.landing.team') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Live avatar initials from name input
var nameInput = document.getElementById('nameInput');
var avatarInitials = document.getElementById('avatarInitials');
var avatarImg = document.getElementById('avatarImg');
var removeBtn = document.getElementById('removePhotoBtn');

nameInput && nameInput.addEventListener('input', function() {
    var v = this.value.trim();
    if (avatarInitials && avatarImg.style.display === 'none') {
        avatarInitials.textContent = v ? v.charAt(0).toUpperCase() : '?';
    }
});

// Photo file picker
document.getElementById('photoInput').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        avatarImg.src = e.target.result;
        avatarImg.style.display = 'block';
        avatarInitials.style.display = 'none';
        if (removeBtn) removeBtn.style.display = 'inline-flex';
    };
    reader.readAsDataURL(file);
});

function removePhoto() {
    avatarImg.src = '';
    avatarImg.style.display = 'none';
    avatarInitials.style.display = 'flex';
    document.getElementById('photoInput').value = '';
    var flag = document.getElementById('removePhotoFlag');
    if (flag) flag.value = '1';
    if (removeBtn) removeBtn.style.display = 'none';
}
</script>
@endpush

@endsection
