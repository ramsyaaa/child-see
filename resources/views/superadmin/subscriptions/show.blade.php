@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.subscriptions.index') }}">Subscriptions</a></li>
                    <li class="breadcrumb-item" aria-current="page">Details</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Subscription Details</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Subscription Information</h5>
                <div>
                    <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Member:</strong></div>
                    <div class="col-md-8">{{ $subscription->user->name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Product:</strong></div>
                    <div class="col-md-8">{{ $subscription->product->name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Start Date:</strong></div>
                    <div class="col-md-8">{{ $subscription->start_date->format('d M Y') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>End Date:</strong></div>
                    <div class="col-md-8">{{ $subscription->end_date->format('d M Y') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Quota Allocated:</strong></div>
                    <div class="col-md-8">{{ $subscription->quota_allocated ?? 'Unlimited' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Quota Used:</strong></div>
                    <div class="col-md-8">{{ $subscription->quota_used }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Remaining Quota:</strong></div>
                    <div class="col-md-8">
                        <span class="badge bg-light-primary">
                            {{ $subscription->getRemainingQuota() }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Status:</strong></div>
                    <div class="col-md-8">
                        @if($subscription->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($subscription->status == 'expired')
                            <span class="badge bg-secondary">Expired</span>
                        @else
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Created At:</strong></div>
                    <div class="col-md-8">{{ $subscription->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Usage Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Bookings:</span>
                        <strong class="text-primary">{{ $subscription->bookings->count() }}</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Confirmed Bookings:</span>
                        <strong class="text-success">{{ $subscription->bookings->whereIn('status', ['booked','completed'])->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

