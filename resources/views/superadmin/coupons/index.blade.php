@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Coupons</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Coupon / Discount Codes</h2>
                    <a href="{{ route('superadmin.coupons.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Coupon
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Search Code / Description</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="e.g. SAVE20">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Type</label>
                <select name="type" class="form-select">
                    <option value="">All</option>
                    <option value="percentage" @selected(request('type')=='percentage')>Percentage</option>
                    <option value="nominal" @selected(request('type')=='nominal')>Nominal</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="active" @selected(request('status')=='active')>Active</option>
                    <option value="inactive" @selected(request('status')=='inactive')>Inactive</option>
                    <option value="expired" @selected(request('status')=='expired')>Expired</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Search
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('superadmin.coupons.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Purchase</th>
                        <th>Usage</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td><strong class="font-monospace">{{ $coupon->code }}</strong></td>
                            <td>{{ $coupon->description ?? '—' }}</td>
                            <td>
                                @if($coupon->type === 'percentage')
                                    <span class="badge bg-info text-dark">Percentage</span>
                                @else
                                    <span class="badge bg-secondary">Nominal</span>
                                @endif
                            </td>
                            <td>
                                @if($coupon->type === 'percentage')
                                    <strong>{{ rtrim(rtrim(number_format($coupon->value, 2), '0'), '.') }}%</strong>
                                @else
                                    <strong>Rp {{ number_format($coupon->value, 0, ',', '.') }}</strong>
                                @endif
                            </td>
                            <td>
                                @if($coupon->min_purchase)
                                    Rp {{ number_format($coupon->min_purchase, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                {{ $coupon->used_count }}
                                @if($coupon->max_uses)
                                    / {{ $coupon->max_uses }}
                                @else
                                    <small class="text-muted">/ ∞</small>
                                @endif
                            </td>
                            <td>
                                @if($coupon->expires_at)
                                    <span class="{{ $coupon->expires_at->isPast() ? 'text-danger' : '' }}">
                                        {{ $coupon->expires_at->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">Never</span>
                                @endif
                            </td>
                            <td>
                                @if(!$coupon->is_active)
                                    <span class="badge bg-secondary">Inactive</span>
                                @elseif($coupon->expires_at && $coupon->expires_at->isPast())
                                    <span class="badge bg-warning text-dark">Expired</span>
                                @elseif($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses)
                                    <span class="badge bg-warning text-dark">Exhausted</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('superadmin.coupons.edit', $coupon) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($coupon->used_count == 0)
                                        <form action="{{ route('superadmin.coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-tags fa-2x mb-2 d-block opacity-25"></i>
                                No coupons found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection
