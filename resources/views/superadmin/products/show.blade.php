@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ $product->name }}</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">{{ $product->name }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Product Details</h5>
                <div>
                    <a href="{{ route('superadmin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('superadmin.products.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Name:</strong></div>
                    <div class="col-md-8">{{ $product->name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Type:</strong></div>
                    <div class="col-md-8">
                        <span class="badge bg-{{ $product->type == 'subscription' ? 'primary' : ($product->type == 'dropin' ? 'info' : 'warning') }}">
                            {{ ucfirst($product->type) }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Description:</strong></div>
                    <div class="col-md-8">{{ $product->description ?? 'N/A' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Price:</strong></div>
                    <div class="col-md-8">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Duration:</strong></div>
                    <div class="col-md-8">{{ $product->duration_days ?? 'N/A' }} days</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Quota:</strong></div>
                    <div class="col-md-8">{{ $product->quota ?? 'Unlimited' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Status:</strong></div>
                    <div class="col-md-8">
                        @if($product->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Created At:</strong></div>
                    <div class="col-md-8">{{ $product->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Last Updated:</strong></div>
                    <div class="col-md-8">{{ $product->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Subscriptions:</span>
                        <strong class="text-primary">{{ $product->subscriptions->count() }}</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Active Subscriptions:</span>
                        <strong class="text-success">{{ $product->subscriptions->where('status', 'active')->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

