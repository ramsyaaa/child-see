@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.coupons.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item" aria-current="page">Create Coupon</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Create Coupon</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('superadmin.coupons.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control text-uppercase @error('code') is-invalid @enderror"
                                   value="{{ strtoupper(old('code')) }}" placeholder="e.g. SAVE20" required
                                   oninput="this.value=this.value.toUpperCase()" style="font-family:monospace;font-size:1.1em">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Uppercase letters, numbers, and hyphens only. Must be unique.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                                   value="{{ old('description') }}" placeholder="e.g. 20% off for new members">
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                            <select name="type" id="discountType" class="form-select @error('type') is-invalid @enderror" required onchange="toggleValueHelp()">
                                <option value="">— Select —</option>
                                <option value="percentage" @selected(old('type')=='percentage')>Percentage (%)</option>
                                <option value="nominal" @selected(old('type')=='nominal')>Nominal (Rp flat)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="valuePrefix">%</span>
                                <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                                       value="{{ old('value') }}" step="0.01" min="0" placeholder="0" required>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted" id="valueHelp">Enter percentage (0–100) or IDR amount.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Minimum Purchase (Rp)</label>
                            <input type="number" name="min_purchase" class="form-control @error('min_purchase') is-invalid @enderror"
                                   value="{{ old('min_purchase') }}" step="1000" min="0" placeholder="Leave blank for none">
                            @error('min_purchase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Max Uses</label>
                            <input type="number" name="max_uses" class="form-control @error('max_uses') is-invalid @enderror"
                                   value="{{ old('max_uses') }}" min="1" placeholder="Leave blank for unlimited">
                            @error('max_uses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Expiry Date</label>
                            <input type="date" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror"
                                   value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                       value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="isActive">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Coupon
                        </button>
                        <a href="{{ route('superadmin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Coupon Guide</h6>
                <ul class="small text-muted mb-0 ps-3">
                    <li class="mb-2"><strong>Percentage</strong>: e.g. 20% off any order. Value must be 0–100.</li>
                    <li class="mb-2"><strong>Nominal</strong>: e.g. Rp 50,000 off. Discount will not exceed the order total.</li>
                    <li class="mb-2"><strong>Min Purchase</strong>: Coupon won't apply if order is below this amount.</li>
                    <li class="mb-2"><strong>Max Uses</strong>: Total number of times this code can be redeemed across all members.</li>
                    <li><strong>Expiry Date</strong>: Code becomes invalid after midnight on this date.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleValueHelp() {
    const type = document.getElementById('discountType').value;
    const prefix = document.getElementById('valuePrefix');
    const help   = document.getElementById('valueHelp');
    if (type === 'percentage') {
        prefix.textContent = '%';
        help.textContent   = 'Enter a value between 0 and 100.';
        document.querySelector('[name=value]').max = 100;
    } else if (type === 'nominal') {
        prefix.textContent = 'Rp';
        help.textContent   = 'Enter the flat discount amount in IDR.';
        document.querySelector('[name=value]').max = '';
    } else {
        prefix.textContent = '%';
        help.textContent   = 'Select a type first.';
    }
}
// init on page load in case of validation error repopulation
toggleValueHelp();
</script>
@endpush
@endsection
