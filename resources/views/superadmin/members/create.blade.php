@extends('superadmin.layout.master')
@section('title', 'Buat Pengguna Baru')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.members.index') }}">Pengguna</a></li>
                    <li class="breadcrumb-item active">Buat Baru</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title"><h5 class="m-b-10">Buat Pengguna Baru</h5></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-xl-7">
        <div class="card">
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li style="font-size:.85rem;">{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('superadmin.members.store') }}" method="POST" id="createUserForm">
                    @csrf

                    {{-- Role selector --}}
                    @php
                        $roleOptions = [
                            'MEMBER'       => ['label'=>'Orang Tua / Guru',    'icon'=>'ti-users',    'color'=>'#8E77AB', 'desc'=>'Akses asesmen 1 anak'],
                            'ORGANIZATION' => ['label'=>'Organisasi / Sekolah','icon'=>'ti-building', 'color'=>'#8499B6', 'desc'=>'Multi-anak, kuota kustom'],
                            'ADMIN'        => ['label'=>'Admin',                'icon'=>'ti-shield',   'color'=>'#6B5A8E', 'desc'=>'Akses panel superadmin'],
                        ];
                        $selectedRole = old('role', 'MEMBER');
                    @endphp

                    {{-- Real radios — hidden with display:none so they still submit but never intercept clicks --}}
                    @foreach($roleOptions as $roleVal => $roleMeta)
                        <input type="radio" name="role" value="{{ $roleVal }}" id="role_{{ $roleVal }}"
                               style="display:none;" {{ $selectedRole===$roleVal ? 'checked' : '' }}>
                    @endforeach

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Role Pengguna <span class="text-danger">*</span></label>
                        <div style="display:flex;flex-wrap:wrap;gap:.6rem;">
                            @foreach($roleOptions as $roleVal => $roleMeta)
                            <div id="rolecard_{{ $roleVal }}"
                                 onclick="selectRole('{{ $roleVal }}')"
                                 style="flex:1;min-width:140px;cursor:pointer;border-radius:12px;padding:1rem .85rem;text-align:center;
                                        border:2px solid {{ $selectedRole===$roleVal ? $roleMeta['color'] : 'rgba(142,119,171,.2)' }};
                                        background:{{ $selectedRole===$roleVal ? $roleMeta['color'].'14' : '#f8f7fc' }};
                                        transition:border-color .18s,background .18s;
                                        user-select:none;">
                                <i class="ti {{ $roleMeta['icon'] }}" style="font-size:1.5rem;color:{{ $roleMeta['color'] }};display:block;margin-bottom:.4rem;pointer-events:none;"></i>
                                <div style="font-size:.8rem;font-weight:700;color:#2E2046;margin-bottom:.2rem;pointer-events:none;">{{ $roleMeta['label'] }}</div>
                                <div style="font-size:.7rem;color:#9ca3af;pointer-events:none;">{{ $roleMeta['desc'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-3">
                    <p class="fw-semibold small text-uppercase text-muted mb-3" style="letter-spacing:.05em;">Informasi Akun</p>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Budi Santoso" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Username <span class="text-danger">*</span>
                                <span class="fw-normal text-muted" style="font-size:.75rem;">(untuk login)</span>
                            </label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}" placeholder="budi.santoso" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="nama@email.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">No. Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+62 812-xxxx-xxxx">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select">
                                <option value="ACTIVE" selected>Aktif</option>
                                <option value="SUSPENDED">Ditangguhkan</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold">Kuota Anak</label>
                            <input type="number" name="child_quota" class="form-control" value="{{ old('child_quota', 1) }}" min="1" max="500">
                        </div>
                    </div>

                    {{-- Organization fields — shown conditionally --}}
                    <div id="orgFields" style="display:{{ old('role') === 'ORGANIZATION' ? 'block' : 'none' }};">
                        <hr class="my-3">
                        <p class="fw-semibold small text-uppercase text-muted mb-3" style="letter-spacing:.05em;">Informasi Organisasi</p>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Nama Organisasi / Sekolah <span class="text-danger">*</span></label>
                                <input type="text" name="organization_name" class="form-control @error('organization_name') is-invalid @enderror"
                                       value="{{ old('organization_name') }}" placeholder="SD Negeri 01...">
                                @error('organization_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                                <select name="organization_type" class="form-select @error('organization_type') is-invalid @enderror">
                                    <option value="">— Pilih —</option>
                                    @foreach(['Sekolah Dasar (SD)','Sekolah Luar Biasa (SLB)','Yayasan Pendidikan','Komunitas','Klinik / Terapi','Lainnya'] as $t)
                                        <option value="{{ $t }}" {{ old('organization_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                                @error('organization_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">
                    <p class="fw-semibold small text-uppercase text-muted mb-3" style="letter-spacing:.05em;">Password</p>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 karakter" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-user-plus me-1"></i>Buat Pengguna
                        </button>
                        <a href="{{ route('superadmin.members.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
var roleColors = {
    'MEMBER':       '#8E77AB',
    'ORGANIZATION': '#8499B6',
    'ADMIN':        '#6B5A8E'
};

function selectRole(val) {
    // Check the matching hidden radio
    var radio = document.getElementById('role_' + val);
    if (radio) radio.checked = true;

    // Update every card's border + background
    ['MEMBER', 'ORGANIZATION', 'ADMIN'].forEach(function(r) {
        var card = document.getElementById('rolecard_' + r);
        if (!card) return;
        var color = roleColors[r];
        if (r === val) {
            card.style.borderColor = color;
            card.style.background  = color + '14';
        } else {
            card.style.borderColor = 'rgba(142,119,171,.2)';
            card.style.background  = '#f8f7fc';
        }
    });

    // Show/hide org fields
    var orgFields = document.getElementById('orgFields');
    if (orgFields) {
        orgFields.style.display = (val === 'ORGANIZATION') ? 'block' : 'none';
    }

    // Smart quota default
    var quota = document.querySelector('[name="child_quota"]');
    if (quota) {
        if (val === 'ORGANIZATION' && (quota.value === '1' || quota.value === '')) {
            quota.value = '30';
        } else if (val !== 'ORGANIZATION' && quota.value === '30') {
            quota.value = '1';
        }
    }
}

// Run on load to restore old() state after validation failure
(function() {
    var checked = document.querySelector('input[name="role"]:checked');
    if (checked) selectRole(checked.value);
})();
</script>
@endsection
@endsection
