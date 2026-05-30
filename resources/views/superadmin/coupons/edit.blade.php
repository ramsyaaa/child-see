@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.coupons.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item" aria-current="page">Edit {{ $coupon->code }}</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Edit Coupon: <code>{{ $coupon->code }}</code></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('superadmin.coupons.update', $coupon) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control text-uppercase @error('code') is-invalid @enderror"
                                   value="{{ strtoupper(old('code', $coupon->code)) }}" required
                                   oninput="this.value=this.value.toUpperCase()" style="font-family:monospace;font-size:1.1em">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                                   value="{{ old('description', $coupon->description) }}">
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                            <select name="type" id="discountType" class="form-select @error('type') is-invalid @enderror" required onchange="toggleValueHelp()">
                                <option value="percentage" @selected(old('type', $coupon->type)=='percentage')>Percentage (%)</option>
                                <option value="nominal" @selected(old('type', $coupon->type)=='nominal')>Nominal (Rp flat)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="valuePrefix">{{ $coupon->type === 'percentage' ? '%' : 'Rp' }}</span>
                                <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                                       value="{{ old('value', $coupon->value) }}" step="0.01" min="0" required>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Minimum Purchase (Rp)</label>
                            <input type="number" name="min_purchase" class="form-control @error('min_purchase') is-invalid @enderror"
                                   value="{{ old('min_purchase', $coupon->min_purchase) }}" step="1000" min="0" placeholder="Leave blank for none">
                            @error('min_purchase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Max Uses</label>
                            <input type="number" name="max_uses" class="form-control @error('max_uses') is-invalid @enderror"
                                   value="{{ old('max_uses', $coupon->max_uses) }}" min="1" placeholder="Unlimited">
                            @error('max_uses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($coupon->used_count > 0)
                                <small class="text-muted">Used {{ $coupon->used_count }} times so far.</small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Expiry Date</label>
                            <input type="date" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror"
                                   value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                       value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="isActive">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Coupon
                        </button>
                        <a href="{{ route('superadmin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Stats</h5></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-6">Used</dt>
                    <dd class="col-6">{{ $coupon->used_count }}{{ $coupon->max_uses ? ' / ' . $coupon->max_uses : '' }}</dd>
                    <dt class="col-6">Created</dt>
                    <dd class="col-6">{{ $coupon->created_at->format('d M Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleValueHelp() {
    const type   = document.getElementById('discountType').value;
    const prefix = document.getElementById('valuePrefix');
    if (type === 'percentage') {
        prefix.textContent = '%';
        document.querySelector('[name=value]').max = 100;
    } else {
        prefix.textContent = 'Rp';
        document.querySelector('[name=value]').max = '';
    }
}
toggleValueHelp();
</script>
@endpush
@endsection
