@extends('superadmin.layout.master')
@section('title', 'Edit Pengguna')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.members.index') }}">Pengguna</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.members.show', $member) }}">{{ $member->name }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title"><h5 class="m-b-10">Edit Pengguna</h5></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-3">
                <div class="avtar avtar-xs" style="background:#8E77AB22;">
                    <span style="color:#8E77AB;font-weight:700;">{{ strtoupper(substr($member->name,0,1)) }}</span>
                </div>
                <div>
                    <div class="fw-semibold">{{ $member->name }}</div>
                    <div class="text-muted small">{{ $member->getRoleLabel() }}</div>
                </div>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif

                <form action="{{ route('superadmin.members.update', $member) }}" method="POST">
                    @csrf @method('PUT')

                    <p class="fw-semibold small text-uppercase text-muted mb-3" style="letter-spacing:.05em;">Informasi Akun</p>

                    {{-- Role badge (read-only) --}}
                    <div class="mb-3 p-3 rounded-3" style="background:#8E77AB0D;border:1px solid #8E77AB22;">
                        <span class="small text-muted">Role saat ini:</span>
                        <span class="badge ms-2 rounded-pill" style="background:#8E77AB18;color:#4A3769;border:1px solid #8E77AB33;font-size:.78rem;padding:.35rem .65rem;">{{ $member->getRoleLabel() }}</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $member->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Username
                                <span class="fw-normal text-muted" style="font-size:.75rem;">(untuk login)</span>
                            </label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username', $member->username) }}" placeholder="username">
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $member->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">No. Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}" placeholder="+62...">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="ACTIVE"    @selected(strtoupper(old('status',$member->status))==='ACTIVE')>Aktif</option>
                                <option value="SUSPENDED" @selected(strtoupper(old('status',$member->status))==='SUSPENDED')>Ditangguhkan</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold">Kuota Anak</label>
                            <input type="number" name="child_quota" class="form-control" value="{{ old('child_quota', $member->child_quota ?? 1) }}" min="1" max="500">
                            <div class="form-text">Default: 1 untuk orang tua.</div>
                        </div>
                    </div>

                    @if(strtoupper($member->role) === 'ORGANIZATION')
                    <hr class="my-3">
                    <h6 class="fw-semibold text-muted mb-3 small text-uppercase tracking-wide">Informasi Organisasi</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Organisasi</label>
                            <input type="text" name="organization_name" class="form-control"
                                   value="{{ old('organization_name', $member->organization_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipe Organisasi</label>
                            <select name="organization_type" class="form-select">
                                @foreach(['Sekolah Dasar (SD)','Sekolah Luar Biasa (SLB)','Yayasan Pendidikan','Komunitas','Klinik / Terapi','Lainnya'] as $type)
                                    <option value="{{ $type }}" @selected(old('organization_type',$member->organization_type)===$type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <hr class="my-3">
                    <h6 class="fw-semibold text-muted mb-1 small text-uppercase tracking-wide">Ganti Password</h6>
                    <p class="text-muted small mb-3">Kosongkan jika tidak ingin mengganti password.</p>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   autocomplete="new-password" placeholder="Min. 8 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('superadmin.members.show', $member) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
