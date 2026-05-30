@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item" aria-current="page">Create</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Create Product</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Product Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.products.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="subscription" {{ old('type') == 'subscription' ? 'selected' : '' }}>Subscription</option>
                            <option value="dropin" {{ old('type') == 'dropin' ? 'selected' : '' }}>Drop-in</option>
                            <option value="bundle" {{ old('type') == 'bundle' ? 'selected' : '' }}>Bundle</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', 0) }}" min="0" step="1000" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="duration_days" class="form-label">Duration (Days)</label>
                            <input type="number" class="form-control @error('duration_days') is-invalid @enderror" id="duration_days" name="duration_days" value="{{ old('duration_days', 30) }}" min="1">
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quota_type" class="form-label">Quota Type</label>
                            <select class="form-select @error('quota_type') is-invalid @enderror" id="quota_type" name="quota_type">
                                <option value="unlimited" {{ old('quota_type', 'unlimited') == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                <option value="limited" {{ old('quota_type') == 'limited' ? 'selected' : '' }}>Limited</option>
                            </select>
                            @error('quota_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="quota" class="form-label">Quota</label>
                            <input type="number" class="form-control @error('quota') is-invalid @enderror" id="quota" name="quota" value="{{ old('quota') }}" min="1">
                            @error('quota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty for unlimited</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_popular" name="is_popular" {{ old('is_popular') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_popular">
                                <i class="fas fa-star text-warning me-1"></i> Mark as Popular
                                <small class="text-muted d-block">Highlights this package with a "Most Popular" badge on the landing page</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('superadmin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                            <i class="fas fa-save me-2"></i>Create Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

