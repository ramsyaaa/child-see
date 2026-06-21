@extends('superadmin.layout.master')
@section('page-title', 'Website Settings')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.dashboard') }}">Pengaturan</a></li>
            <li class="active">Website Settings</li>
        </ul>
        <h2>Website Settings</h2>
        <p class="inklu-subtitle">Identitas, kontak, SEO, dan tampilan situs Child See</p>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">

        <!-- Identitas -->
        <div class="col-md-8">
            <div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
                <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                    <h6 class="mb-0" style="color:#4A3769;">Identitas Situs</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Nama Situs</label>
                            <input name="site_name" value="{{ $settings['site_name'] ?? 'Child See' }}" class="form-control" style="border-color:#BAA6D6;" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Tagline</label>
                            <input name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}" class="form-control" style="border-color:#BAA6D6;">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Logo Situs</label>
                            @if(!empty($settings['site_logo']))
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" style="height:50px;border-radius:6px;border:1px solid rgba(186,166,214,0.3);" alt="Logo">
                            </div>
                            @endif
                            <input type="file" name="site_logo" accept="image/*" class="form-control" style="border-color:#BAA6D6;">
                            <small style="color:rgba(74,55,105,0.50);">Disarankan PNG transparan, min. 200px. Maks. 5MB. Biarkan kosong untuk tidak mengubah.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div class="card mt-3" style="border:1.5px solid rgba(186,166,214,0.25);">
                <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                    <h6 class="mb-0" style="color:#4A3769;">Kontak & Alamat</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Email</label>
                            <input name="site_email" type="email" value="{{ $settings['site_email'] ?? '' }}" class="form-control" style="border-color:#BAA6D6;" placeholder="halo@Child See.id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Telepon / WhatsApp</label>
                            <input name="site_phone" value="{{ $settings['site_phone'] ?? '' }}" class="form-control" style="border-color:#BAA6D6;" placeholder="+62 812-xxxx-xxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Alamat</label>
                            <input name="site_address" value="{{ $settings['site_address'] ?? '' }}" class="form-control" style="border-color:#BAA6D6;" placeholder="Bandung, Jawa Barat">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sosial Media -->
            <div class="card mt-3" style="border:1.5px solid rgba(186,166,214,0.25);">
                <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                    <h6 class="mb-0" style="color:#4A3769;">Sosial Media</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach(['site_instagram'=>['Instagram','fab fa-instagram'],'site_facebook'=>['Facebook','fab fa-facebook'],'site_whatsapp'=>['WhatsApp (link)','fab fa-whatsapp'],'site_youtube'=>['YouTube','fab fa-youtube']] as $key=>[$label,$icon])
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;"><i class="{{ $icon }}" style="width:16px;"></i> {{ $label }}</label>
                            <input name="{{ $key }}" value="{{ $settings[$key] ?? '' }}" class="form-control" style="border-color:#BAA6D6;" placeholder="https://…">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO -->
        <div class="col-md-4">
            <div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
                <div class="card-header" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
                    <h6 class="mb-0" style="color:#4A3769;">SEO & Meta</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Meta Title <small style="font-weight:400;color:rgba(74,55,105,0.45);">(max 120)</small></label>
                        <input name="seo_title" id="seoTitle" value="{{ $settings['seo_title'] ?? '' }}" class="form-control" style="border-color:#BAA6D6;" maxlength="120" oninput="updatePreview()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Meta Description <small style="font-weight:400;color:rgba(74,55,105,0.45);">(max 320)</small></label>
                        <textarea name="seo_description" id="seoDesc" rows="3" class="form-control" style="border-color:#BAA6D6;" maxlength="320" oninput="updatePreview()">{{ $settings['seo_description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">Keywords</label>
                        <input name="seo_keywords" value="{{ $settings['seo_keywords'] ?? '' }}" class="form-control" style="border-color:#BAA6D6;" placeholder="kata kunci, dipisah koma">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.8rem;color:rgba(74,55,105,0.75);font-weight:600;">OG Image (Open Graph)</label>
                        @if(!empty($settings['og_image']))
                        <div class="mb-2"><img src="{{ asset('storage/' . $settings['og_image']) }}" style="width:100%;border-radius:6px;border:1px solid rgba(186,166,214,0.3);" alt="OG"></div>
                        @endif
                        <input type="file" name="og_image" accept="image/*" class="form-control" style="border-color:#BAA6D6;">
                        <small style="color:rgba(74,55,105,0.50);">Disarankan 1200×630px. Maks. 5MB.</small>
                    </div>

                    <!-- Google SERP Preview -->
                    <div style="background:#fff;border:1px solid #dfe1e5;border-radius:8px;padding:1rem;margin-top:0.5rem;">
                        <div style="font-size:0.7rem;color:rgba(74,55,105,0.50);margin-bottom:0.5rem;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;">Pratinjau Google</div>
                        <div style="font-size:0.8rem;color:#1a0dab;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" id="prev-title">{{ $settings['seo_title'] ?? 'Judul Halaman' }}</div>
                        <div style="font-size:0.7rem;color:#006621;margin:2px 0;">Child See.id</div>
                        <div style="font-size:0.75rem;color:#545454;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;" id="prev-desc">{{ $settings['seo_description'] ?? 'Deskripsi halaman akan muncul di sini.' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="d-flex justify-content-end mt-3 gap-2">
        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn" style="background:#5C477F;color:#fff;font-weight:600;padding:0.5rem 1.5rem;">
            <i class="fas fa-save me-1"></i> Simpan Pengaturan
        </button>
    </div>
</form>

@push('scripts')
<script>
function updatePreview() {
    document.getElementById('prev-title').textContent = document.getElementById('seoTitle').value || 'Judul Halaman';
    document.getElementById('prev-desc').textContent  = document.getElementById('seoDesc').value  || 'Deskripsi halaman.';
}
</script>
@endpush
@endsection
